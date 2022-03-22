<?php

namespace Filament\Tables\Columns\Concerns;

use Illuminate\Support\Str;

trait CanBeSortable
{
    /**
     * @var bool
     */
    protected $isSortable = false;

    /**
     * @var mixed[]|null
     */
    protected $sortColumns = [];

    /**
     * @param mixed[]|bool $condition
     * @return $this
     */
    public function sortable($condition = true)
    {
        if (is_array($condition)) {
            $this->isSortable = true;
            $this->sortColumns = $condition;
        } else {
            $this->isSortable = $condition;
            $this->sortColumns = null;
        }

        return $this;
    }

    public function getSortColumns(): array
    {
        return $this->sortColumns ?? $this->getDefaultSortColumns();
    }

    public function isSortable(): bool
    {
        return $this->isSortable;
    }

    protected function getDefaultSortColumns(): array
    {
        return [Str::of($this->getName())->afterLast('.')];
    }
}
