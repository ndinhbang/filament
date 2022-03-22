<?php

namespace Filament\Tables\Filters\Concerns;

trait HasName
{
    /**
     * @var string
     */
    protected $name;

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
