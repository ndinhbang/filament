<?php

namespace Filament\Forms\Components\Builder;

use Closure;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Concerns;
use Illuminate\Support\Str;

class Block extends Component
{
    use Concerns\HasName;

    /**
     * @var string
     */
    protected $view = 'forms::components.builder.block';

    /**
     * @var \Closure|string|null
     */
    protected $icon = null;

    final public function __construct(string $name)
    {
        $this->name($name);
    }

    /**
     * @return $this
     */
    public static function make(string $name)
    {
        return app(static::class, ['name' => $name]);
    }

    /**
     * @param \Closure|string|null $icon
     * @return $this
     */
    public function icon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->evaluate($this->icon);
    }

    public function getLabel(): ?string
    {
        return parent::getLabel() ?? (string) Str::of($this->getName())
                ->kebab()
                ->replace(['-', '_'], ' ')
                ->ucfirst();
    }
}
