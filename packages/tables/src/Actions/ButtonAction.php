<?php

namespace Filament\Tables\Actions;

use Closure;

class ButtonAction extends Action
{
    use Concerns\CanBeOutlined;

    protected string $view = 'tables::actions.button-action';

    /**
     * @var \Closure|string|null
     */
    protected $iconPosition = null;

    /**
     * @param \Closure|string|null $position
     * @return $this
     */
    public function iconPosition($position)
    {
        $this->iconPosition = $position;

        return $this;
    }

    public function getIconPosition(): ?string
    {
        return $this->evaluate($this->iconPosition);
    }
}
