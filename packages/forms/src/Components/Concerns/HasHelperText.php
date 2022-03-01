<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait HasHelperText
{
    /**
     * @var \Closure|string|null
     */
    protected $helperText = null;

    /**
     * @param \Closure|string|null $text
     * @return $this
     */
    public function helperText($text)
    {
        $this->helperText = $text;

        return $this;
    }

    public function getHelperText(): ?string
    {
        return $this->evaluate($this->helperText);
    }
}
