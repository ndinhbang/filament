<?php

namespace Filament\Forms\Components;

use Closure;
use Illuminate\Contracts\Support\Arrayable;

class Radio extends Field
{
    use Concerns\HasExtraInputAttributes;

    protected string $view = 'forms::components.radio';

    /**
     * @var bool|\Closure
     */
    protected $isInline = false;

    /**
     * @var mixed[]|\Closure|\Illuminate\Contracts\Support\Arrayable
     */
    protected $options = [];

    /**
     * @var mixed[]|\Closure|\Illuminate\Contracts\Support\Arrayable
     */
    protected $descriptions = [];

    /**
     * @var bool|\Closure|null
     */
    protected $isOptionDisabled = null;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return $this
     */
    public function boolean(string $trueLabel = 'Yes', string $falseLabel = 'No')
    {
        $this->options([
            1 => $trueLabel,
            0 => $falseLabel,
        ]);

        return $this;
    }

    /**
     * @param bool|\Closure $callback
     * @return $this
     */
    public function disableOptionWhen($callback)
    {
        $this->isOptionDisabled = $callback;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function inline($condition = true)
    {
        $this->isInline = $condition;

        return $this;
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

    /**
     * @param mixed[]|\Closure|\Illuminate\Contracts\Support\Arrayable $descriptions
     * @return $this
     */
    public function descriptions($descriptions)
    {
        $this->descriptions = $descriptions;

        return $this;
    }

    public function hasDescription($value): bool
    {
        return array_key_exists($value, $this->getDescriptions());
    }

    public function getDescription($value): ?string
    {
        return $this->getDescriptions()[$value] ?? null;
    }

    public function getDescriptions(): array
    {
        $descriptions = $this->evaluate($this->descriptions);

        if ($descriptions instanceof Arrayable) {
            $descriptions = $descriptions->toArray();
        }

        return $descriptions;
    }

    public function getOptions(): array
    {
        $options = $this->evaluate($this->options);

        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        return $options;
    }

    public function isInline(): bool
    {
        return (bool) $this->evaluate($this->isInline);
    }

    public function isOptionDisabled($value, string $label): bool
    {
        if ($this->isOptionDisabled === null) {
            return false;
        }

        return (bool) $this->evaluate($this->isOptionDisabled, [
            'label' => $label,
            'value' => $value,
        ]);
    }
}
