<?php

namespace Filament\Forms\Components\Concerns;

use Closure;
use Illuminate\View\ComponentAttributeBag;

trait HasExtraAlpineAttributes
{
    /**
     * @var mixed[]|\Closure
     */
    protected $extraAlpineAttributes = [];

    /**
     * @param mixed[]|\Closure $attributes
     * @return $this
     */
    public function extraAlpineAttributes($attributes)
    {
        $this->extraAlpineAttributes = $attributes;

        return $this;
    }

    public function getExtraAlpineAttributes(): array
    {
        return $this->evaluate($this->extraAlpineAttributes);
    }

    public function getExtraAlpineAttributeBag(): ComponentAttributeBag
    {
        return new ComponentAttributeBag($this->getExtraAlpineAttributes());
    }
}
