<?php

namespace Filament\Forms\Components;

use Closure;
use function Filament\Forms\array_move_after;
use function Filament\Forms\array_move_before;
use Filament\Forms\ComponentContainer;
use Illuminate\Support\Str;

class Repeater extends Field
{
    use Concerns\CanLimitItemsLength;

    protected string $view = 'forms::components.repeater';

    /**
     * @var \Closure|string|null
     */
    protected $createItemButtonLabel = null;

    /**
     * @var bool|\Closure
     */
    protected $isItemCreationDisabled = false;

    /**
     * @var bool|\Closure
     */
    protected $isItemDeletionDisabled = false;

    /**
     * @var bool|\Closure
     */
    protected $isItemMovementDisabled = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defaultItems(1);

        $this->afterStateHydrated(function (Repeater $component, ?array $state): void {
            $items = collect($state ?? [])
                ->mapWithKeys(fn ($itemData) => [(string) Str::uuid() => $itemData])
                ->toArray();

            $component->state($items);
        });

        $this->registerListeners([
            'repeater::createItem' => [
                function (Repeater $component, string $statePath): void {
                    if ($component->isDisabled()) {
                        return;
                    }

                    if ($statePath !== $component->getStatePath()) {
                        return;
                    }

                    $newUuid = (string) Str::uuid();

                    $livewire = $component->getLivewire();
                    data_set($livewire, "{$statePath}.{$newUuid}", []);

                    $component->getChildComponentContainers()[$newUuid]->fill();

                    $component->hydrateDefaultItemState($newUuid);
                },
            ],
            'repeater::deleteItem' => [
                function (Repeater $component, string $statePath, string $uuidToDelete): void {
                    if ($component->isDisabled()) {
                        return;
                    }

                    if ($statePath !== $component->getStatePath()) {
                        return;
                    }

                    $items = $component->getState();

                    unset($items[$uuidToDelete]);

                    $livewire = $component->getLivewire();
                    data_set($livewire, $statePath, $items);
                },
            ],
            'repeater::moveItemDown' => [
                function (Repeater $component, string $statePath, string $uuidToMoveDown): void {
                    if ($component->isDisabled()) {
                        return;
                    }

                    if ($component->isItemMovementDisabled()) {
                        return;
                    }

                    if ($statePath !== $component->getStatePath()) {
                        return;
                    }

                    $items = array_move_after($component->getState(), $uuidToMoveDown);

                    $livewire = $component->getLivewire();
                    data_set($livewire, $statePath, $items);
                },
            ],
            'repeater::moveItemUp' => [
                function (Repeater $component, string $statePath, string $uuidToMoveUp): void {
                    if ($component->isDisabled()) {
                        return;
                    }

                    if ($component->isItemMovementDisabled()) {
                        return;
                    }

                    if ($statePath !== $component->getStatePath()) {
                        return;
                    }

                    $items = array_move_before($component->getState(), $uuidToMoveUp);

                    $livewire = $component->getLivewire();
                    data_set($livewire, $statePath, $items);
                },
            ],
        ]);

        $this->createItemButtonLabel(function (Repeater $component) {
            return __('forms::components.repeater.buttons.create_item.label', [
                'label' => lcfirst($component->getLabel()),
            ]);
        });

        $this->mutateDehydratedStateUsing(function (?array $state): array {
            return array_values($state ?? []);
        });
    }

    /**
     * @param \Closure|string|null $label
     * @return $this
     */
    public function createItemButtonLabel($label)
    {
        $this->createItemButtonLabel = $label;

        return $this;
    }

    /**
     * @param \Closure|int $count
     * @return $this
     */
    public function defaultItems($count)
    {
        $this->default(function (Repeater $component) use ($count): array {
            $items = [];

            $count = $component->evaluate($count);

            if (! $count) {
                return $items;
            }

            foreach (range(1, $count) as $index) {
                $items[] = [];
            }

            return $items;
        });

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function disableItemCreation($condition = true)
    {
        $this->isItemCreationDisabled = $condition;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function disableItemDeletion($condition = true)
    {
        $this->isItemDeletionDisabled = $condition;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function disableItemMovement($condition = true)
    {
        $this->isItemMovementDisabled = $condition;

        return $this;
    }

    public function hydrateDefaultItemState(string $uuid): void
    {
        $this->getChildComponentContainers()[$uuid]->hydrateDefaultState();
    }

    public function getChildComponentContainers(bool $withHidden = false): array
    {
        return collect($this->getState())
            ->map(function ($itemData, $itemIndex): ComponentContainer {
                return $this
                    ->getChildComponentContainer()
                    ->getClone()
                    ->statePath($itemIndex);
            })
            ->toArray();
    }

    public function getCreateItemButtonLabel(): string
    {
        return $this->evaluate($this->createItemButtonLabel);
    }

    public function isItemCreationDisabled(): bool
    {
        return $this->evaluate($this->isItemCreationDisabled);
    }

    public function isItemDeletionDisabled(): bool
    {
        return $this->evaluate($this->isItemDeletionDisabled);
    }

    public function isItemMovementDisabled(): bool
    {
        return $this->evaluate($this->isItemMovementDisabled);
    }
}
