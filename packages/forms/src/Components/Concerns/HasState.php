<?php

namespace Filament\Forms\Components\Concerns;

use Closure;
use Filament\Forms\Components\Component;

trait HasState
{
    protected ?Closure $afterStateHydrated = null;

    protected ?Closure $afterStateUpdated = null;

    protected ?Closure $beforeStateDehydrated = null;

    protected $defaultState = null;

    protected ?Closure $dehydrateStateUsing = null;

    protected ?Closure $mutateDehydratedStateUsing = null;

    protected bool $hasDefaultState = false;

    /**
     * @var bool|\Closure
     */
    protected $isDehydrated = true;

    protected ?string $statePath = null;

    /**
     * @return $this
     */
    public function afterStateHydrated(?Closure $callback)
    {
        $this->afterStateHydrated = $callback;

        return $this;
    }

    /**
     * @return $this
     */
    public function afterStateUpdated(?Closure $callback)
    {
        $this->afterStateUpdated = $callback;

        return $this;
    }

    /**
     * @return $this
     */
    public function beforeStateDehydrated(?Closure $callback)
    {
        $this->beforeStateDehydrated = $callback;

        return $this;
    }

    /**
     * @return $this
     */
    public function callAfterStateHydrated()
    {
        if ($callback = $this->afterStateHydrated) {
            $this->evaluate($callback);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function callAfterStateUpdated()
    {
        if ($callback = $this->afterStateUpdated) {
            $this->evaluate($callback);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function callBeforeStateDehydrated()
    {
        if ($callback = $this->beforeStateDehydrated) {
            $this->evaluate($callback);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function default($state)
    {
        $this->defaultState = $state;
        $this->hasDefaultState = true;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function dehydrated($condition = true)
    {
        $this->isDehydrated = $condition;

        return $this;
    }

    public function dehydrateState()
    {
        if ($callback = $this->dehydrateStateUsing) {
            return $this->evaluate($callback);
        }

        return $this->getState();
    }

    /**
     * @return $this
     */
    public function dehydrateStateUsing(?Closure $callback)
    {
        $this->dehydrateStateUsing = $callback;

        return $this;
    }

    /**
     * @return $this
     */
    public function hydrateDefaultState()
    {
        if (! $this->hasDefaultState()) {
            return $this;
        }

        $this->state($this->getDefaultState());

        return $this;
    }

    /**
     * @return $this
     */
    public function fillMissingStateWithNull()
    {
        $livewire = $this->getLivewire();

        data_fill($livewire, $this->getStatePath(), null);

        return $this;
    }

    public function mutateDehydratedState($state)
    {
        return $this->evaluate(
            $this->mutateDehydratedStateUsing,
            ['state' => $state]
        );
    }

    public function mutatesDehydratedState(): bool
    {
        return $this->mutateDehydratedStateUsing instanceof Closure;
    }

    /**
     * @return $this
     */
    public function mutateDehydratedStateUsing(?Closure $callback)
    {
        $this->mutateDehydratedStateUsing = $callback;

        return $this;
    }

    /**
     * @return $this
     */
    public function state($state)
    {
        $livewire = $this->getLivewire();

        data_set($livewire, $this->getStatePath(), $this->evaluate($state));

        return $this;
    }

    /**
     * @return $this
     */
    public function statePath(?string $path)
    {
        $this->statePath = $path;

        return $this;
    }

    public function getDefaultState()
    {
        return $this->evaluate($this->defaultState);
    }

    public function getState()
    {
        $state = data_get($this->getLivewire(), $this->getStatePath());

        if (is_array($state)) {
            return $state;
        }

        if (blank($state)) {
            return null;
        }

        return $state;
    }

    public function getStatePath(bool $isAbsolute = true): string
    {
        $pathComponents = [];

        if ($isAbsolute && ($containerStatePath = $this->getContainer()->getStatePath())) {
            $pathComponents[] = $containerStatePath;
        }

        if (filled($statePath = $this->statePath)) {
            $pathComponents[] = $statePath;
        }

        return implode('.', $pathComponents);
    }

    protected function hasDefaultState(): bool
    {
        return $this->hasDefaultState;
    }

    public function isDehydrated(): bool
    {
        return (bool) $this->evaluate($this->isDehydrated);
    }

    protected function getGetCallback(): Closure
    {
        return function ($path, bool $isAbsolute = false) {
            if ($path instanceof Component) {
                $path = $path->getStatePath();
            } elseif (
                (! $isAbsolute) &&
                ($containerPath = $this->getContainer()->getStatePath())
            ) {
                $path = "{$containerPath}.{$path}";
            }

            $livewire = $this->getLivewire();

            return data_get($livewire, $path);
        };
    }

    protected function getSetCallback(): Closure
    {
        return function ($path, $state, bool $isAbsolute = false) {
            if ($path instanceof Component) {
                $path = $path->getStatePath();
            } elseif (
                (! $isAbsolute) &&
                ($containerPath = $this->getContainer()->getStatePath())
            ) {
                $path = "{$containerPath}.{$path}";
            }

            $livewire = $this->getLivewire();
            data_set($livewire, $path, $this->evaluate($state));

            return $state;
        };
    }
}
