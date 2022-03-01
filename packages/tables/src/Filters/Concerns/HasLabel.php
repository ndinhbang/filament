<?php

namespace Filament\Tables\Filters\Concerns;

use Closure;
use Illuminate\Support\Str;

trait HasLabel
{
    /**
     * @var \Closure|string|null
     */
    protected $label = null;

    /**
     * @param \Closure|string|null $label
     * @return $this
     */
    public function label($label)
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->evaluate($this->label) ?? (string) Str::of($this->getName())
            ->before('.')
            ->kebab()
            ->replace(['-', '_'], ' ')
            ->ucfirst();
    }
}
