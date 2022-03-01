<?php

namespace Filament\Forms\Components;

use Closure;

class Textarea extends Field
{
    use Concerns\CanBeAutocapitalized;
    use Concerns\CanBeAutocompleted;
    use Concerns\CanBeLengthConstrained;
    use Concerns\HasExtraAlpineAttributes;
    use Concerns\HasExtraInputAttributes;
    use Concerns\HasPlaceholder;

    protected string $view = 'forms::components.textarea';

    /**
     * @var \Closure|int|null
     */
    protected $cols = null;

    /**
     * @var \Closure|int|null
     */
    protected $rows = null;

    /**
     * @var bool|\Closure
     */
    protected $shouldAutosize = false;

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function autosize($condition = true)
    {
        $this->shouldAutosize = $condition;

        return $this;
    }

    /**
     * @param \Closure|int|null $cols
     * @return $this
     */
    public function cols($cols)
    {
        $this->cols = $cols;

        return $this;
    }

    /**
     * @param \Closure|int|null $rows
     * @return $this
     */
    public function rows($rows)
    {
        $this->rows = $rows;

        return $this;
    }

    public function getCols(): ?int
    {
        return $this->evaluate($this->cols);
    }

    public function getRows(): ?int
    {
        return $this->evaluate($this->rows);
    }

    public function shouldAutosize(): bool
    {
        return $this->rows === null || ((bool) $this->evaluate($this->shouldAutosize));
    }
}
