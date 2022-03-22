<?php

namespace Filament\Forms\Components\Concerns;

use Illuminate\Support\Arr;

trait HasMeta
{
    /**
     * @var mixed[]
     */
    protected $meta = [];

    /**
     * @return $this
     */
    public function meta(string $key, $value)
    {
        $this->meta[$key] = $value;

        return $this;
    }

    /**
     * @param mixed[]|string|null $keys
     */
    public function getMeta($keys = null)
    {
        if (is_array($keys)) {
            return Arr::only($this->meta, $keys);
        }

        if (is_string($keys)) {
            return Arr::get($this->meta, $keys);
        }

        return $this->meta;
    }

    /**
     * @param mixed[]|string $keys
     */
    public function hasMeta($keys): bool
    {
        return Arr::has($this->meta, $keys);
    }
}
