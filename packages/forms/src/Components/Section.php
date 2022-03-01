<?php

namespace Filament\Forms\Components;

use Closure;
use Illuminate\Support\Str;

class Section extends Component implements Contracts\CanConcealComponents
{
    use Concerns\HasExtraAlpineAttributes;

    protected string $view = 'forms::components.section';

    /**
     * @var bool|\Closure
     */
    protected $isCollapsed = false;

    /**
     * @var bool|\Closure
     */
    protected $isCollapsible = false;

    /**
     * @var \Closure|string|null
     */
    protected $description = null;

    /**
     * @var \Closure|string
     */
    protected $heading;

    /**
     * @param \Closure|string $heading
     */
    final public function __construct($heading)
    {
        $this->heading($heading);
    }

    /**
     * @param \Closure|string $heading
     * @return $this
     */
    public static function make($heading)
    {
        $static = app(static::class, ['heading' => $heading]);
        $static->setUp();

        return $static;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->columnSpan('full');
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function collapsed($condition = true)
    {
        $this->isCollapsed = $condition;
        $this->collapsible(true);

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function collapsible($condition = true)
    {
        $this->isCollapsible = $condition;

        return $this;
    }

    /**
     * @param \Closure|string|null $description
     * @return $this
     */
    public function description($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param \Closure|string $heading
     * @return $this
     */
    public function heading($heading)
    {
        $this->heading = $heading;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->evaluate($this->description);
    }

    public function getHeading(): string
    {
        return $this->evaluate($this->heading);
    }

    public function getId(): ?string
    {
        $id = parent::getId();

        if (! $id) {
            $id = Str::slug($this->getHeading());

            if ($statePath = $this->getStatePath()) {
                $id = "{$statePath}.{$id}";
            }
        }

        return $id;
    }

    public function isCollapsed(): bool
    {
        return (bool) $this->evaluate($this->isCollapsed);
    }

    public function isCollapsible(): bool
    {
        return (bool) $this->evaluate($this->isCollapsible);
    }
}
