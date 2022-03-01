<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait CanBeLengthConstrained
{
    /**
     * @var \Closure|int|null
     */
    protected $maxLength = null;

    /**
     * @var \Closure|int|null
     */
    protected $minLength = null;

    /**
     * @param \Closure|int $length
     * @return $this
     */
    public function maxLength($length)
    {
        $this->maxLength = $length;

        $this->rule(function (): string {
            $length = $this->getMaxLength();

            return "max:{$length}";
        });

        return $this;
    }

    /**
     * @param \Closure|int $length
     * @return $this
     */
    public function minLength($length)
    {
        $this->minLength = $length;

        $this->rule(function (): string {
            $length = $this->getMinLength();

            return "min:{$length}";
        });

        return $this;
    }

    public function getMaxLength(): ?int
    {
        return $this->evaluate($this->maxLength);
    }

    public function getMinLength(): ?int
    {
        return $this->evaluate($this->minLength);
    }
}
