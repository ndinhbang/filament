<?php

namespace Filament\Tables\Filters\Concerns;

trait HasName
{
    protected string $name;

    /**
     * @return $this
     */
    public function name(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
