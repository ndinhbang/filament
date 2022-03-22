<?php

namespace Filament\Forms\Components;

use Closure;

class Toggle extends Field
{
    use Concerns\CanBeAccepted;
    use Concerns\CanBeInline;
    use Concerns\HasExtraAlpineAttributes;

    /**
     * @var string
     */
    protected $view = 'forms::components.toggle';

    /**
     * @var \Closure|string|null
     */
    protected $offIcon = null;

    /**
     * @var \Closure|string|null
     */
    protected $onIcon = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->default(false);

        $this->afterStateHydrated(function (Toggle $component, $state): void {
            $component->state((bool) $state);
        });

        $this->rule('boolean');
    }

    /**
     * @param \Closure|string|null $icon
     * @return $this
     */
    public function offIcon($icon)
    {
        $this->offIcon = $icon;

        return $this;
    }

    /**
     * @param \Closure|string|null $icon
     * @return $this
     */
    public function onIcon($icon)
    {
        $this->onIcon = $icon;

        return $this;
    }

    public function getOffIcon(): ?string
    {
        return $this->evaluate($this->offIcon);
    }

    public function getOnIcon(): ?string
    {
        return $this->evaluate($this->onIcon);
    }

    public function hasOffIcon(): bool
    {
        return (bool) $this->getOffIcon();
    }

    public function hasOnIcon(): bool
    {
        return (bool) $this->getOnIcon();
    }
}
