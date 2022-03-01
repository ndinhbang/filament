<?php

namespace Filament\Pages\Actions\Modal\Actions\Concerns;

use Illuminate\Support\Str;

trait HasLabel
{
    protected ?string $label = null;

    /**
     * @return $this
     */
    public function label(string $label)
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label ?? (string) Str::of($this->getName())
            ->before('.')
            ->kebab()
            ->replace(['-', '_'], ' ')
            ->ucfirst();
    }
}
