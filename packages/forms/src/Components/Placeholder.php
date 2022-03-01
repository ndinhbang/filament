<?php

namespace Filament\Forms\Components;

use Illuminate\Support\Str;

class Placeholder extends Component
{
    use Concerns\HasHelperText;
    use Concerns\HasHint;
    use Concerns\HasName;

    protected string $view = 'forms::components.placeholder';

    protected $content = null;

    final public function __construct(string $name)
    {
        $this->name($name);
        $this->statePath($name);
    }

    /**
     * @return $this
     */
    public static function make(string $name)
    {
        $static = app(static::class, ['name' => $name]);
        $static->setUp();

        return $static;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrated(false);
    }

    /**
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    protected function shouldEvaluateWithState(): bool
    {
        return false;
    }

    public function getId(): string
    {
        return parent::getId() ?? $this->getStatePath();
    }

    public function getLabel(): string
    {
        return parent::getLabel() ?? (string) Str::of($this->getName())
            ->kebab()
            ->replace(['-', '_'], ' ')
            ->ucfirst();
    }

    public function getContent()
    {
        return $this->evaluate($this->content);
    }
}
