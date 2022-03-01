<?php

namespace Filament\Tables\Columns\Concerns;

use Closure;

trait CanOpenUrl
{
    /**
     * @var bool|\Closure
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
     * @param bool|\Closure $shouldOpenInNewTab
     * @return $this
     */
    public function url($url, $shouldOpenInNewTab = false)
    {
        $this->shouldOpenUrlInNewTab = $shouldOpenInNewTab;
        $this->url = $url;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->evaluate($this->url);
    }

    public function shouldOpenUrlInNewTab(): bool
    {
        return $this->evaluate($this->shouldOpenUrlInNewTab);
    }
}
