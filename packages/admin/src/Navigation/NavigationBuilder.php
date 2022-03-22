<?php

namespace Filament\Navigation;

class NavigationBuilder
{
    /** @var array<string, \Filament\Navigation\NavigationItem[]> */
    protected $groups = [];

    /** @var \Filament\Navigation\NavigationItem[] */
    protected $items = [];

    /**
     * @return $this
     */
    public function group(string $name, array $items = [])
    {
        $this->groups[$name] = collect($items)->map(
            function (NavigationItem $item, int $index) use ($name) {
                return $item->group($name)->sort($index);
            }
        )->toArray();

        return $this;
    }

    /**
     * @return $this
     */
    public function item(NavigationItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /** @param \Filament\Navigation\NavigationItem[] $items
     * @return $this */
    public function items(array $items)
    {
        foreach ($items as $item) {
            $this->item($item);
        }

        return $this;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
