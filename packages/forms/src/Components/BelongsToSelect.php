<?php

namespace Filament\Forms\Components;

use Closure;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BelongsToSelect extends Select
{
    /**
     * @var \Closure|string|null
     */
    protected $displayColumnName = null;

    /**
     * @var bool|\Closure
     */
    protected $isPreloaded = false;

    /**
     * @var \Closure|string|null
     */
    protected $relationship = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function (BelongsToSelect $component): void {
            if (filled($this->getState())) {
                return;
            }

            $relationship = $component->getRelationship();
            $relatedModel = $relationship->getResults();

            if (! $relatedModel) {
                return;
            }

            $component->state(
                $relatedModel->getAttribute(
                    $relationship->getOwnerKeyName()
                )
            );
        });

        $this->saveRelationshipsUsing(function (BelongsToSelect $component, Model $record, $state) {
            $component->getRelationship()->associate($state);
            $record->save();
        });
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function preload($condition = true)
    {
        $this->isPreloaded = $condition;

        return $this;
    }

    /**
     * @return mixed[]
     */
    public function getSearchColumns(): ?array
    {
        return $this->searchColumns ?? [$this->getDisplayColumnName()];
    }

    /**
     * @param \Closure|string $relationshipName
     * @param \Closure|string $displayColumnName
     * @return $this
     */
    public function relationship($relationshipName, $displayColumnName, ?Closure $callback = null)
    {
        $this->displayColumnName = $displayColumnName;
        $this->relationship = $relationshipName;

        $this->getOptionLabelUsing(function (BelongsToSelect $component, $value) {
            $relationship = $component->getRelationship();

            $record = $relationship->getRelated()->query()->where($relationship->getOwnerKeyName(), $value)->first();

            return ($record2 = $record) ? $record2->getAttributeValue($component->getDisplayColumnName()) : null;
        });

        $this->getSearchResultsUsing(function (BelongsToSelect $component, ?string $query) use ($callback): array {
            $relationship = $component->getRelationship();

            $relationshipQuery = $relationship->getRelated()->query()->orderBy($component->getDisplayColumnName());

            if ($callback) {
                $relationshipQuery = $this->evaluate($callback, [
                    'query' => $relationshipQuery,
                ]);
            }

            $query = strtolower($query);

            return $this->applySearchConstraint($relationshipQuery, $query)
                ->limit(50)
                ->pluck($component->getDisplayColumnName(), $relationship->getOwnerKeyName())
                ->toArray();
        });

        $this->options(function (BelongsToSelect $component) use ($callback): array {
            if ($component->isSearchable() && ! $component->isPreloaded()) {
                return [];
            }

            $relationship = $component->getRelationship();

            $relationshipQuery = $relationship->getRelated()->query()->orderBy($component->getDisplayColumnName());

            if ($callback) {
                $relationshipQuery = $this->evaluate($callback, [
                    'query' => $relationshipQuery,
                ]);
            }

            return $relationshipQuery
                ->pluck($component->getDisplayColumnName(), $relationship->getOwnerKeyName())
                ->toArray();
        });

        $this->exists(
            function (BelongsToSelect $component) : ?string {
                return ($relationship = $component->getRelationship()) ? get_class($relationship->getModel()) : null;
            },
            function (BelongsToSelect $component) : string {
                return $component->getRelationship()->getOwnerKeyName();
            }
        );

        return $this;
    }

    protected function applySearchConstraint(Builder $query, string $searchQuery): Builder
    {
        /** @var Connection $databaseConnection */
        $databaseConnection = $query->getConnection();

        switch ($databaseConnection->getDriverName()) {
            case 'pgsql':
                $searchOperator = 'ilike';
                break;
            default:
                $searchOperator = 'like';
                break;
        }

        $isFirst = true;

        foreach ($this->getSearchColumns() as $searchColumnName) {
            $whereClause = $isFirst ? 'where' : 'orWhere';

            $query->{$whereClause}(
                $searchColumnName,
                $searchOperator,
                "%{$searchQuery}%"
            );

            $isFirst = false;
        }

        return $query;
    }

    public function getDisplayColumnName(): string
    {
        return $this->evaluate($this->displayColumnName);
    }

    public function getLabel(): ?string
    {
        if ($this->label === null) {
            return (string) Str::of($this->getRelationshipName())
                ->before('.')
                ->kebab()
                ->replace(['-', '_'], ' ')
                ->ucfirst();
        }

        return parent::getLabel();
    }

    public function getRelationship(): ?BelongsTo
    {
        return $this->getModelInstance()->{$this->getRelationshipName()}();
    }

    public function getRelationshipName(): string
    {
        return $this->evaluate($this->relationship);
    }

    public function isPreloaded(): bool
    {
        return $this->evaluate($this->isPreloaded);
    }
}
