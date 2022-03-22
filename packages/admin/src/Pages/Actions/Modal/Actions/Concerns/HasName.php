<?php

namespace Filament\Pages\Actions\Modal\Actions\Concerns;

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
