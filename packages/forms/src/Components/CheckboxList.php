<?php

namespace Filament\Forms\Components;

use Closure;
use Illuminate\Contracts\Support\Arrayable;

class CheckboxList extends Field
{
    protected string $view = 'forms::components.checkbox-list';

    /**
     * @var mixed[]|\Closure
     */
    protected $options = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->default([]);

        $this->afterStateHydrated(function (CheckboxList $component, $state) {
            if (is_array($state)) {
                return;
            }

            $component->state([]);
        });
    }

    /**
     * @param mixed[]|\Closure|\Illuminate\Contracts\Support\Arrayable $options
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

        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        return $options;
    }
}
