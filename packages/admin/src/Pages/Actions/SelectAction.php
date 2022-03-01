<?php

namespace Filament\Pages\Actions;

use Illuminate\Contracts\Support\Arrayable;

class SelectAction extends Action
{
    use Concerns\HasId;

    protected string $view = 'filament::pages.actions.select-action';

    /**
     * @var mixed[]|\Illuminate\Contracts\Support\Arrayable
     */
    protected $options = [];

    protected ?string $placeholder = null;

    /**
     * @param mixed[]|\Illuminate\Contracts\Support\Arrayable $options
     * @return $this
     */
    public function options($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return $this
     */
    public function placeholder(string $placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getOptions(): array
    {
        $options = $this->options;

        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        return $options;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }
}
