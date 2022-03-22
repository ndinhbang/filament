<?php

namespace Filament\Tables\Filters\Concerns;

use Closure;
use Illuminate\Contracts\Support\Arrayable;

trait HasOptions
{
    /**
     * @var mixed[]|\Closure|\Illuminate\Contracts\Support\Arrayable|string|null
     */
    protected $options = null;

    /**
     * @param mixed[]|\Closure|\Illuminate\Contracts\Support\Arrayable|string|null $options
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

        if ($options === null) {
            $options = $this->queriesRelationships() ? $this->getRelationshipOptions() : [];
        }

        if (is_string($options) && function_exists('enum_exists') && enum_exists($options)) {
            $options = collect($options::cases())->mapWithKeys(function ($case) {
                return [((($case2 = $case) ? $case2->value : null) ?? $case->name) => $case->name];
            });
        }

        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        return $options;
    }
}
