<?php

namespace Filament\Tables\Actions\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasRecord
{
    protected ?Model $record = null;

    /**
     * @return $this
     */
    public function record(?Model $record)
    {
        $this->record = $record;

        return $this;
    }

    public function getRecord(): ?Model
    {
        return $this->record;
    }
}
