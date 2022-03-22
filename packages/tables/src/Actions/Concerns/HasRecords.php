<?php

namespace Filament\Tables\Actions\Concerns;

use Illuminate\Database\Eloquent\Collection;

trait HasRecords
{
    /**
     * @var \Illuminate\Database\Eloquent\Collection|null
     */
    protected $records;

    /**
     * @return $this
     */
    public function records(Collection $records)
    {
        $this->records = $records;

        return $this;
    }

    public function getRecords(): ?Collection
    {
        return $this->records;
    }
}
