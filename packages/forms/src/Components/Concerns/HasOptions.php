<?php

namespace Filament\Forms\Components\Concerns;

use Closure;
use Illuminate\Contracts\Support\Arrayable;

trait HasOptions
{
    /**
     * @var mixed[]|\Closure|\Illuminate\Contracts\Support\Arrayable|string
     */
    protected $options = [];

    /**
     * @param mixed[]|\Closure|\Illuminate\Contracts\Support\Arrayable|string $options
     * @return $this
     */
    public function options($options)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): array
    {
        $options = $this->evaluate($this->options);

        if (is_string($options) && function_exists('enum_exists') && enum_exists($options)) {
            $options = collect($options::cases())->mapWithKeys(fn ($case) => [((($case2 = $case) ? $case2->value : null) ?? $case->name) => $case->name]);
        }

        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        return $options;
    }
}
