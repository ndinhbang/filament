<?php

namespace Filament\Tables\Actions\Modal\Actions\Concerns;

trait CanSubmitForm
{
    protected bool $canSubmitForm = false;

    protected ?string $form = null;

    /**
     * @return $this
     */
    public function submit(?string $form = null)
    {
        $this->canSubmitForm = true;
        $this->form = $form;

        return $this;
    }

    public function canSubmitForm(): bool
    {
        return $this->canSubmitForm;
    }

    public function getForm(): ?string
    {
        return $this->form;
    }
}
