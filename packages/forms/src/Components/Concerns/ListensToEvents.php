<?php

namespace Filament\Forms\Components\Concerns;

trait ListensToEvents
{
    protected array $listeners = [];

    /**
     * @return $this
     */
    public function dispatchEvent(string $event, ...$parameters)
    {
        foreach ($this->getListeners($event) as $callback) {
            $callback($this, ...$parameters);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function registerListeners(array $listeners)
    {
        foreach ($listeners as $event => $callbacks) {
            $this->listeners[$event] = array_merge($this->getListeners($event), $callbacks);
        }

        return $this;
    }

    public function getListeners(string $event = null): array
    {
        $listeners = $this->listeners;

        if ($event) {
            return $listeners[$event] ?? [];
        }

        return $listeners;
    }
}
