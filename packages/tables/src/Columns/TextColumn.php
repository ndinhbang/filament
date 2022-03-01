<?php

namespace Filament\Tables\Columns;

use Closure;

class TextColumn extends Column
{
    use Concerns\CanFormatState;

    protected string $view = 'tables::columns.text-column';

    /**
     * @var bool|\Closure
     */
    protected $canWrap = false;

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function wrap($condition = true)
    {
        $this->canWrap = $condition;

        return $this;
    }

    public function canWrap(): bool
    {
        return $this->evaluate($this->canWrap);
    }

    protected function mutateArrayState(array $state): string
    {
        return implode(', ', $state);
    }
}
