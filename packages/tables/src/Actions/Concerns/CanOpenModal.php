<?php

namespace Filament\Tables\Actions\Concerns;

use Closure;
use Filament\Tables\Actions\Modal\Actions\ButtonAction;

trait CanOpenModal
{
    /**
     * @var bool|\Closure|null
     */
    protected $isModalCentered = null;

    /**
     * @var mixed[]|\Closure|null
     */
    protected $modalActions = null;

    /**
     * @var \Closure|string|null
     */
    protected $modalButtonLabel = null;

    /**
     * @var \Closure|string|null
     */
    protected $modalHeading = null;

    /**
     * @var \Closure|string|null
     */
    protected $modalSubheading = null;

    /**
     * @var \Closure|string|null
     */
    protected $modalWidth = null;

    /**
     * @param bool|\Closure|null $condition
     * @return $this
     */
    public function centerModal($condition = true)
    {
        $this->isModalCentered = $condition;

        return $this;
    }

    /**
     * @param mixed[]|\Closure|null $actions
     * @return $this
     */
    public function modalActions($actions = null)
    {
        $this->modalActions = $actions;

        return $this;
    }

    /**
     * @param \Closure|string|null $label
     * @return $this
     */
    public function modalButton($label = null)
    {
        $this->modalButtonLabel = $label;

        return $this;
    }

    /**
     * @param \Closure|string|null $heading
     * @return $this
     */
    public function modalHeading($heading = null)
    {
        $this->modalHeading = $heading;

        return $this;
    }

    /**
     * @param \Closure|string|null $subheading
     * @return $this
     */
    public function modalSubheading($subheading = null)
    {
        $this->modalSubheading = $subheading;

        return $this;
    }

    /**
     * @param \Closure|string|null $width
     * @return $this
     */
    public function modalWidth($width = null)
    {
        $this->modalWidth = $width;

        return $this;
    }

    public function getModalActions(): array
    {
        if ($this->modalActions !== null) {
            return $this->evaluate($this->modalActions);
        }

        $color = $this->getColor();

        $actions = [
            ButtonAction::make('submit')
                ->label($this->getModalButtonLabel())
                ->submit($this->getLivewireSubmitActionName())
                ->color($color !== 'secondary' ? $color : null),
            ButtonAction::make('cancel')
                ->label(__('tables::table.actions.modal.buttons.cancel.label'))
                ->cancel()
                ->color('secondary'),
        ];

        if ($this->isModalCentered()) {
            $actions = array_reverse($actions);
        }

        return $actions;
    }

    public function getModalButtonLabel(): string
    {
        if (filled($this->modalButtonLabel)) {
            return $this->evaluate($this->modalButtonLabel);
        }

        if ($this->isConfirmationRequired()) {
            return __('tables::table.actions.modal.buttons.confirm.label');
        }

        return __('tables::table.actions.modal.buttons.submit.label');
    }

    public function getModalHeading(): string
    {
        return $this->evaluate($this->modalHeading) ?? $this->getLabel();
    }

    public function getModalSubheading(): ?string
    {
        if (filled($this->modalSubheading)) {
            return $this->evaluate($this->modalSubheading);
        }

        if ($this->isConfirmationRequired()) {
            return __('tables::table.actions.modal.requires_confirmation_subheading');
        }

        return null;
    }

    public function getModalWidth(): string
    {
        if (filled($this->modalWidth)) {
            return $this->evaluate($this->modalWidth);
        }

        if ($this->isConfirmationRequired()) {
            return 'sm';
        }

        return '4xl';
    }

    public function isModalCentered(): bool
    {
        if ($this->isModalCentered !== null) {
            return $this->evaluate($this->isModalCentered);
        }

        if (in_array($this->getModalWidth(), ['xs', 'sm'])) {
            return true;
        }

        return $this->isConfirmationRequired();
    }

    public function shouldOpenModal(): bool
    {
        return $this->isConfirmationRequired() || $this->hasFormSchema();
    }
}
