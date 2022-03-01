<?php

namespace Filament\Forms\Components;

use Closure;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class BelongsToManyCheckboxList extends CheckboxList
{
    /**
     * @var \Closure|string|null
     */
    protected $displayColumnName = null;

    /**
     * @var \Closure|string|null
     */
    protected $relationship = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function (BelongsToManyCheckboxList $component, ?array $state): void {
            if (count($state ?? [])) {
                return;
            }

            $relationship = $component->getRelationship();
            $relatedModels = $relationship->getResults();

            $component->state(
                // Cast the related keys to a string, otherwise Livewire does not
                // know how to handle deselection.
                //
                // https://github.com/laravel-filament/filament/issues/1111
                $relatedModels
                    ->pluck($relationship->getRelatedKeyName())
                    ->map(fn ($key): string => strval($key))
                    ->toArray()
            );
        });

        $this->saveRelationshipsUsing(function (BelongsToManyCheckboxList $component, ?array $state) {
            $component->getRelationship()->sync($state ?? []);
        });

        $this->dehydrated(false);
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

        $this->options(function (BelongsToManyCheckboxList $component) use ($callback): array {
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
}
