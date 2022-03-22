<?php

namespace Filament\Pages\Actions\Concerns;

use Closure;

trait CanOpenUrl
{
    /**
     * @var bool
     */
    protected $shouldOpenUrlInNewTab = false;

    /**
     * @var \Closure|string|null
     */
    protected $url = null;

    /**
     * @return $this
     */
    public function openUrlInNewTab(bool $condition = true)
    {
        $this->shouldOpenUrlInNewTab = $condition;

        return $this;
    }

    /**
     * @param \Closure|string|null $url
     * @return $this
     */
    public function url($url, bool $shouldOpenInNewTab = false)
    {
        $this->shouldOpenUrlInNewTab = $shouldOpenInNewTab;
        $this->url = $url;

        return $this;
    }

    public function getUrl(): ?string
    {
        return value($this->url);
    }

    public function shouldOpenUrlInNewTab(): bool
    {
        return $this->shouldOpenUrlInNewTab;
    }
}
