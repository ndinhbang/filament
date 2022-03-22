<?php

namespace Filament\Forms\Components;

use Closure;

class KeyValue extends Field
{
    use Concerns\HasExtraAlpineAttributes;

    /**
     * @var string
     */
    protected $view = 'forms::components.key-value';

    /**
     * @var \Closure|string|null
     */
    protected $addButtonLabel = null;

    /**
     * @var bool|\Closure
     */
    protected $shouldDisableAddingRows = false;

    /**
     * @var bool|\Closure
     */
    protected $shouldDisableDeletingRows = false;

    /**
     * @var bool|\Closure
     */
    protected $shouldDisableEditingKeys = false;

    /**
     * @var bool|\Closure
     */
    protected $shouldDisableEditingValues = false;

    /**
     * @var \Closure|string|null
     */
    protected $deleteButtonLabel = null;

    /**
     * @var \Closure|string|null
     */
    protected $keyLabel = null;

    /**
     * @var \Closure|string|null
     */
    protected $valueLabel = null;

    /**
     * @var \Closure|string|null
     */
    protected $keyPlaceholder = null;

    /**
     * @var \Closure|string|null
     */
    protected $valuePlaceholder = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->default([]);

        $this->dehydrateStateUsing(function (?array $state) {
            return collect($state ?? [])
                ->filter(function (?string $value, ?string $key) : bool {
                    return filled($key);
                })
                ->map(function (?string $value) : ?string {
                    return filled($value) ? $value : null;
                })
                ->toArray();
        });

        $this->addButtonLabel(__('forms::components.key_value.buttons.add.label'));

        $this->deleteButtonLabel(__('forms::components.key_value.buttons.delete.label'));

        $this->keyLabel(__('forms::components.key_value.fields.key.label'));

        $this->valueLabel(__('forms::components.key_value.fields.value.label'));
    }

    /**
     * @param \Closure|string|null $label
     * @return $this
     */
    public function addButtonLabel($label)
    {
        $this->addButtonLabel = $label;

        return $this;
    }

    /**
     * @param \Closure|string|null $label
     * @return $this
     */
    public function deleteButtonLabel($label)
    {
        $this->deleteButtonLabel = $label;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function disableAddingRows($condition = true)
    {
        $this->shouldDisableAddingRows = $condition;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function disableDeletingRows($condition = true)
    {
        $this->shouldDisableDeletingRows = $condition;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function disableEditingKeys($condition = true)
    {
        $this->shouldDisableEditingKeys = $condition;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function disableEditingValues($condition = true)
    {
        $this->shouldDisableEditingValues = $condition;

        return $this;
    }

    /**
     * @param \Closure|string|null $label
     * @return $this
     */
    public function keyLabel($label)
    {
        $this->keyLabel = $label;

        return $this;
    }

    /**
     * @param \Closure|string|null $label
     * @return $this
     */
    public function valueLabel($label)
    {
        $this->valueLabel = $label;

        return $this;
    }

    /**
     * @param \Closure|string|null $placeholder
     * @return $this
     */
    public function keyPlaceholder($placeholder)
    {
        $this->keyPlaceholder = $placeholder;

        return $this;
    }

    /**
     * @param \Closure|string|null $placeholder
     * @return $this
     */
    public function valuePlaceholder($placeholder)
    {
        $this->valuePlaceholder = $placeholder;

        return $this;
    }

    public function canAddRows(): bool
    {
        return ! $this->evaluate($this->shouldDisableAddingRows);
    }

    public function canDeleteRows(): bool
    {
        return ! $this->evaluate($this->shouldDisableDeletingRows);
    }

    public function canEditKeys(): bool
    {
        return ! $this->evaluate($this->shouldDisableEditingKeys);
    }

    public function canEditValues(): bool
    {
        return ! $this->evaluate($this->shouldDisableEditingValues);
    }

    public function getAddButtonLabel(): string
    {
        return $this->evaluate($this->addButtonLabel);
    }

    public function getDeleteButtonLabel(): string
    {
        return $this->evaluate($this->deleteButtonLabel);
    }

    public function getKeyLabel(): string
    {
        return $this->evaluate($this->keyLabel);
    }

    public function getValueLabel(): string
    {
        return $this->evaluate($this->valueLabel);
    }

    public function getKeyPlaceholder(): ?string
    {
        return $this->evaluate($this->keyPlaceholder);
    }

    public function getValuePlaceholder(): ?string
    {
        return $this->evaluate($this->valuePlaceholder);
    }
}
