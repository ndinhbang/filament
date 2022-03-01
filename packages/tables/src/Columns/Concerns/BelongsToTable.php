<?php

namespace Filament\Tables\Columns\Concerns;

use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

trait BelongsToTable
{
    protected Table $table;

    /**
     * @return $this
     */
    public function table(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    public function getTable(): Table
    {
        return $this->table;
    }

    public function getLivewire(): HasTable
    {
        return $this->getTable()->getLivewire();
    }
}
