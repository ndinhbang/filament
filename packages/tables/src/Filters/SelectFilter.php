<?php

namespace Filament\Tables\Filters;

use Closure;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

class SelectFilter extends Filter
{
    use Concerns\HasOptions;
    use Concerns\HasPlaceholder;

    /**
     * @var \Closure|string|null
     */
    protected $column = null;

    /**
     * @var bool|\Closure
     */
    protected $isStatic = false;

    /**
     * @var bool|\Closure
     */
    protected $isSearchable = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->placeholder(__('tables::table.filters.select.placeholder'));
    }

    public function apply(Builder $query, array $data = []): Builder
    {
        if ($this->evaluate($this->isStatic)) {
            return $query;
        }

        if ($this->hasQueryModificationCallback()) {
            return parent::apply($query, $data);
        }

        if (blank($data['value'] ?? null)) {
            return $query;
        }

        if ($this->queriesRelationships()) {
            /** @var BelongsTo $relationship */
            $relationship = $this->getRelationship();

            return $query->whereRelation(
                $this->getRelationshipName(),
                $relationship->getOwnerKeyName(),
                $data['value']
            );
        }

        return $query->where($this->getColumn(), $data['value']);
    }

    /**
     * @param \Closure|string|null $name
     * @return $this
     */
    public function column($name)
    {
        $this->column = $name;

        return $this;
    }

    /**
     * @return $this
     */
    public function relationship(string $relationshipName, string $displayColumnName)
    {
        $this->column("{$relationshipName}.{$displayColumnName}");

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function static($condition = true)
    {
        $this->isStatic = $condition;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function searchable($condition = true)
    {
        $this->isSearchable = $condition;

        return $this;
    }

    public function getColumn(): string
    {
        return $this->evaluate($this->column) ?? $this->getName();
    }

    protected function getRelationshipOptions(): array
    {
        /** @var BelongsTo $relationship */
        $relationship = $this->getRelationship();

        $displayColumnName = $this->getRelationshipDisplayColumnName();

        $relationshipQuery = $relationship->getRelated()->query()->orderBy($displayColumnName);

        return $relationshipQuery
            ->pluck($displayColumnName, $relationship->getOwnerKeyName())
            ->toArray();
    }

    public function getFormSchema(): array
    {
        return $this->formSchema ?? [
            Select::make('value')
                ->label($this->getLabel())
                ->options($this->getOptions())
                ->placeholder($this->getPlaceholder())
                ->default($this->getDefaultState())
                ->searchable($this->isSearchable()),
        ];
    }

    public function isSearchable(): bool
    {
        return (bool) $this->evaluate($this->isSearchable);
    }

    public function queriesRelationships(): bool
    {
        return Str::of($this->getColumn())->contains('.');
    }

    protected function getRelationship(): Relation
    {
        $model = app($this->getTable()->getModel());

        return $model->{$this->getRelationshipName()}();
    }

    protected function getRelationshipName(): string
    {
        return (string) Str::of($this->getColumn())->beforeLast('.');
    }

    protected function getRelationshipDisplayColumnName(): string
    {
        return (string) Str::of($this->getColumn())->afterLast('.');
    }
}
