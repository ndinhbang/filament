<?php

namespace Filament\Forms\Components;

use Closure;
use function Filament\Forms\array_move_after;
use function Filament\Forms\array_move_before;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Builder\Block;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Builder extends Field
{
    use Concerns\CanLimitItemsLength;

    /**
     * @var string
     */
    protected $view = 'forms::components.builder';

    /**
     * @var \Closure|string|null
     */
    protected $createItemBetweenButtonLabel = null;

    /**
     * @var \Closure|string|null
     */
    protected $createItemButtonLabel = null;

    /**
     * @var bool|\Closure
     */
    protected $isItemMovementDisabled = false;

    /**
     * @var bool|\Closure
     */
    protected $isItemCreationDisabled = false;

    /**
     * @var bool|\Closure
     */
    protected $isItemDeletionDisabled = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->default([]);

        $this->afterStateHydrated(function (Builder $component, ?array $state): void {
            $items = collect($state ?? [])
                ->mapWithKeys(function ($itemData) {
                    return [(string) Str::uuid() => $itemData];
                })
                ->toArray();

            $component->state($items);
        });

        $this->registerListeners([
            'builder::createItem' => [
                function (Builder $component, string $statePath, string $block, ?string $afterUuid = null): void {
                    if ($component->isDisabled()) {
                        return;
                    }

                    if ($component->isItemCreationDisabled()) {
                        return;
                    }

                    if ($statePath !== $component->getStatePath()) {
                        return;
                    }

                    $livewire = $component->getLivewire();

                    $newUuid = (string) Str::uuid();
                    $newItem = [
                        'type' => $block,
                        'data' => [],
                    ];

                    if ($afterUuid) {
                        $newItems = [];

                        foreach ($component->getState() as $uuid => $item) {
                            $newItems[$uuid] = $item;

                            if ($uuid === $afterUuid) {
                                $newItems[$newUuid] = $newItem;
                            }
                        }

                        data_set($livewire, $statePath, $newItems);
                    } else {
                        data_set($livewire, "{$statePath}.{$newUuid}", $newItem);
                    }

                    $component->getChildComponentContainers()[$newUuid]->fill();

                    $component->hydrateDefaultItemState($newUuid);
                },
            ],
            'builder::deleteItem' => [
                function (Builder $component, string $statePath, string $uuidToDelete): void {
                    if ($component->isDisabled()) {
                        return;
                    }

                    if ($component->isItemDeletionDisabled()) {
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
            'builder::moveItemDown' => [
                function (Builder $component, string $statePath, string $uuidToMoveDown): void {
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
            'builder::moveItemUp' => [
                function (Builder $component, string $statePath, string $uuidToMoveUp): void {
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

        $this->createItemBetweenButtonLabel(__('forms::components.builder.buttons.create_item_between.label'));

        $this->createItemButtonLabel(function (Builder $component) {
            return __('forms::components.builder.buttons.create_item.label', [
                'label' => lcfirst($component->getLabel()),
            ]);
        });

        $this->mutateDehydratedStateUsing(function (?array $state): array {
            return array_values($state ?? []);
        });
    }

    /**
     * @return $this
     */
    public function blocks(array $blocks)
    {
        $this->childComponents($blocks);

        return $this;
    }

    /**
     * @param \Closure|string|null $label
     * @return $this
     */
    public function createItemBetweenButtonLabel($label)
    {
        $this->createItemBetweenButtonLabel = $label;

        return $this;
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
     * @param bool|\Closure $condition
     * @return $this
     */
    public function disableItemMovement($condition = true)
    {
        $this->isItemMovementDisabled = $condition;

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

    public function hydrateDefaultItemState(string $uuid): void
    {
        $this->getChildComponentContainers()[$uuid]->hydrateDefaultState();
    }

    public function getBlock($name): ?Block
    {
        return Arr::first(
            $this->getBlocks(),
            function (Block $block) use ($name) {
                return $block->getName() === $name;
            }
        );
    }

    public function getBlocks(): array
    {
        return $this->getChildComponentContainer()->getComponents();
    }

    public function getChildComponentContainers(bool $withHidden = false): array
    {
        return collect($this->getState())
            ->map(function ($itemData, $itemIndex): ComponentContainer {
                return $this->getBlock($itemData['type'])
                    ->getChildComponentContainer()
                    ->getClone()
                    ->statePath("{$itemIndex}.data");
            })
            ->toArray();
    }

    public function getCreateItemBetweenButtonLabel(): string
    {
        return $this->evaluate($this->createItemBetweenButtonLabel);
    }

    public function getCreateItemButtonLabel(): string
    {
        return $this->evaluate($this->createItemButtonLabel);
    }

    public function hasBlock($name): bool
    {
        return (bool) $this->getBlock($name);
    }

    public function isItemMovementDisabled(): bool
    {
        return $this->evaluate($this->isItemMovementDisabled);
    }

    public function isItemCreationDisabled(): bool
    {
        return $this->evaluate($this->isItemCreationDisabled);
    }

    public function isItemDeletionDisabled(): bool
    {
        return $this->evaluate($this->isItemDeletionDisabled);
    }
}
