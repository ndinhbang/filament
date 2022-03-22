<?php

namespace Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\TextInput\Mask;
use Illuminate\Contracts\Support\Arrayable;

class TextInput extends Field
{
    use Concerns\CanBeAutocapitalized;
    use Concerns\CanBeAutocompleted;
    use Concerns\CanBeLengthConstrained;
    use Concerns\HasAffixes;
    use Concerns\HasExtraAlpineAttributes;
    use Concerns\HasExtraInputAttributes;
    use Concerns\HasPlaceholder;

    /**
     * @var string
     */
    protected $view = 'forms::components.text-input';

    /**
     * @var \Closure|null
     */
    protected $configureMaskUsing;

    /**
     * @var mixed[]|\Closure|\Illuminate\Contracts\Support\Arrayable|null
     */
    protected $datalistOptions = null;

    /**
     * @var \Closure|string|null
     */
    protected $inputMode = null;

    /**
     * @var bool|\Closure
     */
    protected $isEmail = false;

    /**
     * @var bool|\Closure
     */
    protected $isNumeric = false;

    /**
     * @var bool|\Closure
     */
    protected $isPassword = false;

    /**
     * @var bool|\Closure
     */
    protected $isTel = false;

    /**
     * @var bool|\Closure
     */
    protected $isUrl = false;

    protected $maxValue = null;

    protected $minValue = null;

    /**
     * @var \Closure|float|int|string|null
     */
    protected $step = null;

    /**
     * @var \Closure|string|null
     */
    protected $type = null;

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function currentPassword($condition = true)
    {
        $this->rule('current_password', $condition);

        return $this;
    }

    /**
     * @param mixed[]|\Closure|\Illuminate\Contracts\Support\Arrayable|null $options
     * @return $this
     */
    public function datalist($options)
    {
        $this->datalistOptions = $options;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function email($condition = true)
    {
        $this->isEmail = $condition;

        $this->rule('email', $condition);

        return $this;
    }

    /**
     * @param \Closure|string|null $mode
     * @return $this
     */
    public function inputMode($mode)
    {
        $this->inputMode = $mode;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function integer($condition = true)
    {
        $this->numeric($condition);
        $this->inputMode(function () use ($condition) : ?string {
            return $condition ? 'numeric' : null;
        });
        $this->step(function () use ($condition) : ?int {
            return $condition ? 1 : null;
        });

        return $this;
    }

    /**
     * @return $this
     */
    public function mask(?Closure $configuration)
    {
        $this->configureMaskUsing = $configuration;

        return $this;
    }

    /**
     * @return $this
     */
    public function maxValue($value)
    {
        $this->maxValue = $value;

        $this->rule(function (): string {
            $value = $this->getMaxValue();

            return "max:{$value}";
        });

        return $this;
    }

    /**
     * @return $this
     */
    public function minValue($value)
    {
        $this->minValue = $value;

        $this->rule(function (): string {
            $value = $this->getMinValue();

            return "min:{$value}";
        });

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function numeric($condition = true)
    {
        $this->isNumeric = $condition;

        $this->inputMode(function () use ($condition) : ?string {
            return $condition ? 'decimal' : null;
        });
        $this->rule('numeric', $condition);
        $this->step(function () use ($condition) : ?string {
            return $condition ? 'any' : null;
        });

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function password($condition = true)
    {
        $this->isPassword = $condition;

        return $this;
    }

    /**
     * @param \Closure|float|int|string|null $interval
     * @return $this
     */
    public function step($interval)
    {
        $this->step = $interval;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function tel($condition = true)
    {
        $this->isTel = $condition;

        return $this;
    }

    /**
     * @param \Closure|string|null $type
     * @return $this
     */
    public function type($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function url($condition = true)
    {
        $this->isUrl = $condition;

        $this->rule('url', $condition);

        return $this;
    }

    public function getDatalistOptions(): ?array
    {
        $options = $this->evaluate($this->datalistOptions);

        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        return $options;
    }

    public function getInputMode(): ?string
    {
        return $this->evaluate($this->inputMode);
    }

    public function getMask(): ?Mask
    {
        if (! $this->hasMask()) {
            return null;
        }

        return $this->evaluate($this->configureMaskUsing, [
            'mask' => app(TextInput\Mask::class),
        ]);
    }

    public function getJsonMaskConfiguration(): ?string
    {
        return ($getMask = $this->getMask()) ? $getMask->toJson() : null;
    }

    public function getMaxValue()
    {
        return $this->evaluate($this->maxValue);
    }

    public function getMinValue()
    {
        return $this->evaluate($this->minValue);
    }

    /**
     * @return float|int|string|null
     */
    public function getStep()
    {
        return $this->evaluate($this->step);
    }

    public function getType(): string
    {
        if ($type = $this->evaluate($this->type)) {
            return $type;
        } elseif ($this->isEmail()) {
            return 'email';
        } elseif ($this->isNumeric()) {
            return 'number';
        } elseif ($this->isPassword()) {
            return 'password';
        } elseif ($this->isTel()) {
            return 'tel';
        } elseif ($this->isUrl()) {
            return 'url';
        }

        return 'text';
    }

    public function hasMask(): bool
    {
        return $this->configureMaskUsing !== null;
    }

    public function isEmail(): bool
    {
        return (bool) $this->evaluate($this->isEmail);
    }

    public function isNumeric(): bool
    {
        return (bool) $this->evaluate($this->isNumeric);
    }

    public function isPassword(): bool
    {
        return (bool) $this->evaluate($this->isPassword);
    }

    public function isTel(): bool
    {
        return (bool) $this->evaluate($this->isTel);
    }

    public function isUrl(): bool
    {
        return (bool) $this->evaluate($this->isUrl);
    }
}
