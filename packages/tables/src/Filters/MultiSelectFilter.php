<?php

namespace Filament\Tables\Filters;

use Closure;
use Filament\Forms\Components\MultiSelect;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

class MultiSelectFilter extends Filter
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

    protected function setUp(): void
    {
        parent::setUp();

        $this->placeholder(__('tables::table.filters.multi_select.placeholder'));
    }

    public function apply(Builder $query, array $data = []): Builder
    {
        if ($this->evaluate($this->isStatic)) {
            return $query;
        }

        if ($this->hasQueryModificationCallback()) {
            return parent::apply($query, $data);
        }

        if (blank($data['values'] ?? null)) {
            return $query;
        }

        if ($this->queriesRelationships()) {
            /** @var BelongsTo $relationship */
            $relationship = $this->getRelationship();

            return $query->whereHas(
                $this->getRelationshipName(),
                function (Builder $query) use ($relationship, $data) {
                    return $query->whereIn(
                        $relationship->getOwnerKeyName(),
                        $data['values']
                    );
                }
            );
        }

        /** @var Builder $query */
        $query = $query->whereIn($this->getColumn(), $data['values']);

        return $query;
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
            MultiSelect::make('values')
                ->label($this->getLabel())
                ->options($this->getOptions())
                ->placeholder($this->getPlaceholder())
                ->default($this->getDefaultState()),
        ];
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
