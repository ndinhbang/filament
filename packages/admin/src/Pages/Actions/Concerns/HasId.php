<?php

namespace Filament\Pages\Actions\Concerns;

trait HasId
{
    /**
     * @var string|null
     */
    protected $id;

    /**
     * @return $this
     */
    public function id(string $id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): string
    {
        return $this->id ?? $this->getName();
    }
}
