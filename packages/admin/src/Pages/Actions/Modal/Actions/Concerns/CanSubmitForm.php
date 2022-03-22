<?php

namespace Filament\Pages\Actions\Modal\Actions\Concerns;

trait CanSubmitForm
{
    /**
     * @var bool
     */
    protected $canSubmitForm = false;

    /**
     * @var string|null
     */
    protected $form;

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
