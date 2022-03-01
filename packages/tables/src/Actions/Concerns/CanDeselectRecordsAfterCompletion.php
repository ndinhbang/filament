<?php

namespace Filament\Tables\Actions\Concerns;

use Closure;

trait CanDeselectRecordsAfterCompletion
{
    /**
     * @var bool|\Closure
     */
    protected $shouldDeselectRecordsAfterCompletion = false;

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function deselectRecordsAfterCompletion($condition = true)
    {
        $this->shouldDeselectRecordsAfterCompletion = $condition;

        return $this;
    }

    public function shouldDeselectRecordsAfterCompletion(): bool
    {
        return $this->evaluate($this->shouldDeselectRecordsAfterCompletion);
    }
}
