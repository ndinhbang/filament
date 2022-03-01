<?php

namespace Filament\GlobalSearch;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class GlobalSearchResults
{
    protected Collection $categories;

    final public function __construct()
    {
        $this->categories = Collection::make();
    }

    /**
     * @return $this
     */
    public static function make()
    {
        return new static();
    }

    /**
     * @param mixed[]|\Illuminate\Contracts\Support\Arrayable $results
     * @return $this
     */
    public function category(string $name, $results = [])
    {
        $this->categories[$name] = $results;

        return $this;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }
}
