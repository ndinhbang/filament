<?php

namespace Filament\Tables\Columns\Concerns;

use Akaunting\Money;
use Closure;
use Filament\Tables\Columns\Column;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

trait CanFormatState
{
    protected ?Closure $formatStateUsing = null;

    /**
     * @return $this
     */
    public function date(?string $format = null)
    {
        $format ??= config('tables.date_format');

        $this->formatStateUsing(fn ($state): ?string => $state ? Carbon::parse($state)->translatedFormat($format) : null);

        return $this;
    }

    /**
     * @return $this
     */
    public function dateTime(?string $format = null)
    {
        $format ??= config('tables.date_time_format');

        $this->date($format);

        return $this;
    }

    /**
     * @return $this
     */
    public function enum(array $options, $default = null)
    {
        $this->formatStateUsing(fn ($state): string => $options[$state] ?? ($default ?? $state));

        return $this;
    }

    /**
     * @return $this
     */
    public function limit(int $length = 100, string $end = '...')
    {
        $this->formatStateUsing(function ($state) use ($length, $end): ?string {
            if (blank($state)) {
                return null;
            }

            return Str::limit($state, $length, $end);
        });

        return $this;
    }

    /**
     * @return $this
     */
    public function formatStateUsing(?Closure $callback)
    {
        $this->formatStateUsing = $callback;

        return $this;
    }

    /**
     * @param \Closure|string $currency
     * @return $this
     */
    public function money($currency = 'usd', bool $shouldConvert = false)
    {
        $this->formatStateUsing(function (Column $column, $state) use ($currency, $shouldConvert): ?string {
            if (blank($state)) {
                return null;
            }

            return (new Money\Money(
                $state,
                (new Money\Currency(strtoupper($column->evaluate($currency)))),
                $shouldConvert
            ))->format();
        });

        return $this;
    }

    public function getFormattedState()
    {
        $state = $this->getState();

        if ($this->formatStateUsing) {
            $state = $this->evaluate($this->formatStateUsing, [
                'state' => $state,
            ]);
        }

        return $state;
    }

    /**
     * @return $this
     */
    public function time(?string $format = null)
    {
        $format ??= config('tables.time_format');

        $this->date($format);

        return $this;
    }
}
