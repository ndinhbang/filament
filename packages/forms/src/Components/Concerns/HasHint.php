<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait HasHint
{
    /**
     * @var \Closure|string|null
     */
    protected $hint = null;

    /**
     * @var \Closure|string|null
     */
    protected $hintIcon = null;

    /**
     * @param \Closure|string|null $hint
     * @return $this
     */
    public function hint($hint)
    {
        $this->hint = $hint;

        return $this;
    }

    /**
     * @param \Closure|string|null $hintIcon
     * @return $this
     */
    public function hintIcon($hintIcon)
    {
        $this->hintIcon = $hintIcon;

        return $this;
    }

    public function getHint(): ?string
    {
        return $this->evaluate($this->hint);
    }

    public function getHintIcon(): ?string
    {
        return $this->evaluate($this->hintIcon);
    }
}
