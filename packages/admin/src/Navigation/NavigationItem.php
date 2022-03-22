<?php

namespace Filament\Navigation;

use Closure;

class NavigationItem
{
    /**
     * @var string|null
     */
    protected $group;

    /**
     * @var \Closure|null
     */
    protected $isActiveWhen;

    /**
     * @var string
     */
    protected $icon;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var int|null
     */
    protected $sort;

    /**
     * @var string|null
     */
    protected $url;

    final public function __construct()
    {
    }

    /**
     * @return $this
     */
    public static function make()
    {
        return app(static::class);
    }

    /**
     * @return $this
     */
    public function group(?string $group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return $this
     */
    public function icon(string $icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return $this
     */
    public function isActiveWhen(Closure $callback)
    {
        $this->isActiveWhen = $callback;

        return $this;
    }

    /**
     * @return $this
     */
    public function label(string $label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return $this
     */
    public function sort(?int $sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return $this
     */
    public function url(?string $url)
    {
        $this->url = $url;

        return $this;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getSort(): int
    {
        return $this->sort ?? -1;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function isActive(): bool
    {
        $callback = $this->isActiveWhen;

        if ($callback === null) {
            return false;
        }

        return app()->call($callback);
    }
}
