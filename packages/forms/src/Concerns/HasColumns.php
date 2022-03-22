<?php

namespace Filament\Forms\Concerns;

use Filament\Forms\ComponentContainer;

trait HasColumns
{
    /**
     * @var mixed[]
     */
    protected $columns = [
        'default' => 1,
        'sm' => null,
        'md' => null,
        'lg' => null,
        'xl' => null,
        '2xl' => null,
    ];

    /**
     * @param mixed[]|int|null $columns
     * @return $this
     */
    public function columns($columns = 2)
    {
        if (! is_array($columns)) {
            $columns = [
                'lg' => $columns,
            ];
        }

        $this->columns = array_merge($this->columns, $columns);

        return $this;
    }

    /**
     * @return mixed[]|int|null
     */
    public function getColumns($breakpoint = null)
    {
        if ($this instanceof ComponentContainer && $this->getParentComponent()) {
            $columns = $this->getParentComponent()->getColumns();
        } else {
            $columns = $this->columns;
        }

        if ($breakpoint !== null) {
            $columns = $columns[$breakpoint] ?? null;
        }

        return $columns;
    }
}
