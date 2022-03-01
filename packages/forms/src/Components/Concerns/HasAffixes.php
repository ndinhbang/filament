<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait HasAffixes
{
    /**
     * @var \Closure|string|null
     */
    protected $postfixLabel = null;

    /**
     * @var \Closure|string|null
     */
    protected $prefixLabel = null;

    /**
     * @param \Closure|string|null $label
     * @return $this
     */
    public function prefix($label)
    {
        $this->prefixLabel = $label;

        return $this;
    }

    /**
     * @param \Closure|string|null $label
     * @return $this
     */
    public function postfix($label)
    {
        $this->postfixLabel = $label;

        return $this;
    }

    /**
     * @param \Closure|string|null $label
     * @return $this
     */
    public function suffix($label)
    {
        return $this->postfix($label);
    }

    public function getPrefixLabel()
    {
        return $this->evaluate($this->prefixLabel);
    }

    public function getPostfixLabel()
    {
        return $this->evaluate($this->postfixLabel);
    }
}
