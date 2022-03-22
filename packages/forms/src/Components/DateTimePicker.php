<?php

namespace Filament\Forms\Components;

use Carbon\CarbonInterface;
use Closure;
use DateTime;
use Illuminate\View\ComponentAttributeBag;

class DateTimePicker extends Field
{
    use Concerns\HasExtraAlpineAttributes;
    use Concerns\HasPlaceholder;

    /**
     * @var string
     */
    protected $view = 'forms::components.date-time-picker';

    /**
     * @var \Closure|string|null
     */
    protected $displayFormat = null;

    /**
     * @var mixed[]|\Closure
     */
    protected $extraTriggerAttributes = [];

    /**
     * @var int|null
     */
    protected $firstDayOfWeek = null;

    /**
     * @var \Closure|string|null
     */
    protected $format = null;

    /**
     * @var bool|\Closure
     */
    protected $isWithoutDate = false;

    /**
     * @var bool|\Closure
     */
    protected $isWithoutSeconds = false;

    /**
     * @var bool|\Closure
     */
    protected $isWithoutTime = false;

    /**
     * @var \Closure|\DateTime|string|null
     */
    protected $maxDate = null;

    /**
     * @var \Closure|\DateTime|string|null
     */
    protected $minDate = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function (DateTimePicker $component, $state): void {
            if (! $state instanceof CarbonInterface) {
                return;
            }

            $state = $state->format($component->getFormat());

            $component->state($state);
        });

        $this->rule('date', $this->hasDate());
    }

    /**
     * @param \Closure|string|null $format
     * @return $this
     */
    public function displayFormat($format)
    {
        $this->displayFormat = $format;

        return $this;
    }

    /**
     * @param mixed[]|\Closure $attributes
     * @return $this
     */
    public function extraTriggerAttributes($attributes)
    {
        $this->extraTriggerAttributes = $attributes;

        return $this;
    }

    /**
     * @param int|null $day
     * @return $this
     */
    public function firstDayOfWeek($day)
    {
        if ($day < 0 || $day > 7) {
            $day = null;
        }

        $this->firstDayOfWeek = $day;

        return $this;
    }

    /**
     * @param \Closure|string|null $format
     * @return $this
     */
    public function format($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @param \Closure|\DateTime|string|null $date
     * @return $this
     */
    public function maxDate($date)
    {
        $this->maxDate = $date;

        $this->rule(function () use ($date) {
            $date = $this->evaluate($date);

            if ($date instanceof DateTime) {
                $date = $date->format('Y-m-d');
            }

            return "before_or_equal:{$date}";
        }, function () use ($date) : bool {
            return (bool) $this->evaluate($date);
        });

        return $this;
    }

    /**
     * @param \Closure|\DateTime|string|null $date
     * @return $this
     */
    public function minDate($date)
    {
        $this->minDate = $date;

        $this->rule(function () use ($date) {
            $date = $this->evaluate($date);

            if ($date instanceof DateTime) {
                $date = $date->format('Y-m-d');
            }

            return "after_or_equal:{$date}";
        }, function () use ($date) : bool {
            return (bool) $this->evaluate($date);
        });

        return $this;
    }

    /**
     * @return $this
     */
    public function resetFirstDayOfWeek()
    {
        $this->firstDayOfWeek(null);

        return $this;
    }

    /**
     * @return $this
     */
    public function weekStartsOnMonday()
    {
        $this->firstDayOfWeek(1);

        return $this;
    }

    /**
     * @return $this
     */
    public function weekStartsOnSunday()
    {
        $this->firstDayOfWeek(7);

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function withoutDate($condition = true)
    {
        $this->isWithoutDate = $condition;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function withoutSeconds($condition = true)
    {
        $this->isWithoutSeconds = $condition;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function withoutTime($condition = true)
    {
        $this->isWithoutTime = $condition;

        return $this;
    }

    public function getDisplayFormat(): string
    {
        $format = $this->evaluate($this->displayFormat);

        if ($format) {
            return $format;
        }

        $format = $this->hasDate() ? 'M j, Y' : '';

        if (! $this->hasTime()) {
            return $format;
        }

        $format = $format ? "{$format} H:i" : 'H:i';

        if (! $this->hasSeconds()) {
            return $format;
        }

        return "{$format}:s";
    }

    public function getExtraTriggerAttributes(): array
    {
        return $this->evaluate($this->extraTriggerAttributes);
    }

    public function getExtraTriggerAttributeBag(): ComponentAttributeBag
    {
        return new ComponentAttributeBag($this->getExtraTriggerAttributes());
    }

    public function getFirstDayOfWeek(): int
    {
        return $this->firstDayOfWeek ?? $this->getDefaultFirstDayOfWeek();
    }

    public function getFormat(): string
    {
        $format = $this->evaluate($this->format);

        if ($format) {
            return $format;
        }

        $format = $this->hasDate() ? 'Y-m-d' : '';

        if (! $this->hasTime()) {
            return $format;
        }

        $format = $format ? "{$format} H:i" : 'H:i';

        if (! $this->hasSeconds()) {
            return $format;
        }

        return "{$format}:s";
    }

    public function getMaxDate(): ?string
    {
        $date = $this->evaluate($this->maxDate);

        if ($date instanceof DateTime) {
            $date = $date->format($this->getFormat());
        }

        return $date;
    }

    public function getMinDate(): ?string
    {
        $date = $this->evaluate($this->minDate);

        if ($date instanceof DateTime) {
            $date = $date->format($this->getFormat());
        }

        return $date;
    }

    public function hasDate(): bool
    {
        return ! $this->isWithoutDate;
    }

    public function hasSeconds(): bool
    {
        return ! $this->isWithoutSeconds;
    }

    public function hasTime(): bool
    {
        return ! $this->isWithoutTime;
    }

    protected function getDefaultFirstDayOfWeek(): int
    {
        return config('forms.components.date_time_picker.first_day_of_week', 1);
    }
}
