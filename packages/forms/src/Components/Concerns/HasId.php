<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait HasId
{
    /**
     * @var \Closure|string|null
     */
    protected $id = null;

    /**
     * @param \Closure|string|null $id
     * @return $this
     */
    public function id($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->evaluate($this->id);
    }
}
