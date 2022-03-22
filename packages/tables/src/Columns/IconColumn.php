<?php

namespace Filament\Tables\Columns;

use Closure;
use Illuminate\Contracts\Support\Arrayable;

class IconColumn extends Column
{
    use Concerns\HasColors;

    /**
     * @var string
     */
    protected $view = 'tables::columns.icon-column';

    /**
     * @var mixed[]|\Closure|\Illuminate\Contracts\Support\Arrayable
     */
    protected $options = [];

    /**
     * @param mixed[]|\Closure|\Illuminate\Contracts\Support\Arrayable $options
     * @return $this
     */
    public function options($options)
    {
        $this->options = $options;

        return $this;
    }

    public function getStateIcon(): ?string
    {
        $state = $this->getState();
        $stateIcon = null;

        foreach ($this->getOptions() as $icon => $condition) {
            if (is_numeric($icon)) {
                $stateIcon = $condition;
            } elseif ($condition instanceof Closure && $condition($state)) {
                $stateIcon = $icon;
            } elseif ($condition === $state) {
                $stateIcon = $icon;
            }
        }

        return $stateIcon;
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
