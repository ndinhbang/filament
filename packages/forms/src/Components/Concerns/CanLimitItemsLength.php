<?php

namespace Filament\Forms\Components\Concerns;

use Closure;
use Filament\Forms\Components\Component;

trait CanLimitItemsLength
{
    /**
     * @var \Closure|int|null
     */
    protected $maxItems = null;

    /**
     * @var \Closure|int|null
     */
    protected $minItems = null;

    /**
     * @param \Closure|int|null $count
     * @return $this
     */
    public function maxItems($count)
    {
        $this->maxItems = $count;

        $this->rule('array');
        $this->rule(function (Component $component): string {
            /** @var static $component */

            $count = $component->getMaxItems();

            return "max:{$count}";
        });

        return $this;
    }

    /**
     * @param \Closure|int|null $count
     * @return $this
     */
    public function minItems($count)
    {
        $this->minItems = $count;

        $this->rule('array');
        $this->rule(function (Component $component): string {
            /** @var static $component */

            $count = $component->getMinItems();

            return "min:{$count}";
        });

        return $this;
    }

    public function getMaxItems(): ?int
    {
        return $this->evaluate($this->maxItems);
    }

    public function getMinItems(): ?int
    {
        return $this->evaluate($this->minItems);
    }

    public function getItemsCount(): int
    {
        $state = $this->getState();

        return is_array($state) ? count($state) : 0;
    }
}
