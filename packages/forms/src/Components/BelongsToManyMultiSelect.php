<?php

namespace Filament\Forms\Components;

use Closure;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class BelongsToManyMultiSelect extends MultiSelect
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

        $this->afterStateHydrated(function (BelongsToManyMultiSelect $component, ?array $state): void {
            if (count($state ?? [])) {
                return;
            }

            $relationship = $component->getRelationship();
            $relatedModels = $relationship->getResults();

            $component->state(
                // Cast the related keys to a string, otherwise JavaScript does not
                // know how to handle deselection.
                //
                // https://github.com/laravel-filament/filament/issues/1111
                $relatedModels
                    ->pluck($relationship->getRelatedKeyName())
                    ->map(fn ($key): string => strval($key))
                    ->toArray()
            );
        });

        $this->saveRelationshipsUsing(function (BelongsToManyMultiSelect $component, ?array $state) {
            $component->getRelationship()->sync($state ?? []);
        });

        $this->dehydrated(false);
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
     * @param \Closure|string $relationshipName
     * @param \Closure|string $displayColumnName
     * @return $this
     */
    public function relationship($relationshipName, $displayColumnName, ?Closure $callback = null)
    {
        $this->displayColumnName = $displayColumnName;
        $this->relationship = $relationshipName;

        $this->getOptionLabelsUsing(function (BelongsToManyMultiSelect $component, array $values): array {
            $relationship = $component->getRelationship();
            $relatedKeyName = $relationship->getRelatedKeyName();

            return $relationship->getRelated()->query()
                ->whereIn($relatedKeyName, $values)
                ->pluck($component->getDisplayColumnName(), $relatedKeyName)
                ->toArray();
        });

        $this->getSearchResultsUsing(function (BelongsToManyMultiSelect $component, ?string $query) use ($callback): array {
            $relationship = $component->getRelationship();

            $relationshipQuery = $relationship->getRelated()->query()->orderBy($component->getDisplayColumnName());

            if ($callback) {
                $relationshipQuery = $this->evaluate($callback, [
                    'query' => $relationshipQuery,
                ]);
            }

            $query = strtolower($query);

            /** @var Connection $databaseConnection */
            $databaseConnection = $relationshipQuery->getConnection();

            switch ($databaseConnection->getDriverName()) {
                case 'pgsql':
                    $searchOperator = 'ilike';
                    break;
                default:
                    $searchOperator = 'like';
                    break;
            }

            return $relationshipQuery
                ->where($component->getDisplayColumnName(), $searchOperator, "%{$query}%")
                ->limit(50)
                ->pluck($component->getDisplayColumnName(), $relationship->getRelatedKeyName())
                ->toArray();
        });

        $this->options(function (BelongsToManyMultiSelect $component) use ($callback): array {
            if (! $component->isPreloaded()) {
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
                ->pluck($component->getDisplayColumnName(), $relationship->getRelatedKeyName())
                ->toArray();
        });

        return $this;
    }

    public function getDisplayColumnName(): string
    {
        return $this->evaluate($this->displayColumnName);
    }

    public function getLabel(): string
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

    public function getRelationship(): BelongsToMany
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
