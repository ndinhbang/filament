<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait InteractsWithToolbarButtons
{
    /**
     * @return $this
     */
    public function disableAllToolbarButtons(bool $condition = true)
    {
        if ($condition) {
            $this->toolbarButtons = [];
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function disableToolbarButtons(array $buttonsToDisable = [])
    {
        $this->toolbarButtons = collect($this->getToolbarButtons())
            ->filter(fn ($button) => ! in_array($button, $buttonsToDisable))
            ->toArray();

        return $this;
    }

    /**
     * @return $this
     */
    public function enableToolbarButtons(array $buttonsToEnable = [])
    {
        $this->toolbarButtons = array_merge($this->getToolbarButtons(), $buttonsToEnable);

        return $this;
    }

    /**
     * @param mixed[]|\Closure $buttons
     * @return $this
     */
    public function toolbarButtons($buttons = [])
    {
        $this->toolbarButtons = $buttons;

        return $this;
    }

    public function getToolbarButtons(): array
    {
        return $this->evaluate($this->toolbarButtons);
    }

    /**
     * @param mixed[]|string $button
     */
    public function hasToolbarButton($button): bool
    {
        if (is_array($button)) {
            $buttons = $button;

            return (bool) count(array_intersect($buttons, $this->getToolbarButtons()));
        }

        return in_array($button, $this->getToolbarButtons());
    }
}
