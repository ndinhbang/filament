<?php

namespace Filament\Tables\Columns\Concerns;

use Illuminate\Support\Str;

trait CanBeSearchable
{
    /**
     * @var bool
     */
    protected $isSearchable = false;

    /**
     * @var mixed[]|null
     */
    protected $searchColumns;

    /**
     * @param mixed[]|bool $condition
     * @return $this
     */
    public function searchable($condition = true)
    {
        if (is_array($condition)) {
            $this->isSearchable = true;
            $this->searchColumns = $condition;
        } else {
            $this->isSearchable = $condition;
            $this->searchColumns = null;
        }

        return $this;
    }

    public function getSearchColumns(): array
    {
        return $this->searchColumns ?? $this->getDefaultSearchColumns();
    }

    public function isSearchable(): bool
    {
        return $this->isSearchable;
    }

    protected function getDefaultSearchColumns(): array
    {
        return [Str::of($this->getName())->afterLast('.')];
    }
}
