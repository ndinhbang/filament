<?php

namespace Filament\Forms\Components\Concerns;

use Closure;

trait CanSpanColumns
{
    /**
     * @var mixed[]
     */
    protected $columnSpan = [
        'default' => 1,
        'sm' => null,
        'md' => null,
        'lg' => null,
        'xl' => null,
        '2xl' => null,
    ];

    /**
     * @param mixed[]|\Closure|int|string|null $span
     * @return $this
     */
    public function columnSpan($span)
    {
        if (! is_array($span)) {
            $span = [
                'default' => $span,
            ];
        }

        $this->columnSpan = array_merge($this->columnSpan, $span);

        return $this;
    }

    /**
     * @return mixed[]|int|string|null
     */
    public function getColumnSpan($breakpoint = null)
    {
        $span = $this->columnSpan;

        if ($breakpoint !== null) {
            return $this->evaluate($span[$breakpoint] ?? null);
        }

        return array_map(
            function ($value) {
                return $this->evaluate($value);
            },
            $span
        );
    }
}
