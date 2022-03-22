<?php

namespace Filament\Pages\Actions\Modal\Actions\Concerns;

trait CanBeOutlined
{
    /**
     * @var bool
     */
    protected $isOutlined = false;

    /**
     * @return $this
     */
    public function outlined(bool $condition = true)
    {
        $this->isOutlined = $condition;

        return $this;
    }

    public function isOutlined(): bool
    {
        return $this->isOutlined;
    }
}
