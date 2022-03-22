<?php

namespace Filament\Resources\Pages\Concerns;

use Filament\Pages\Concerns\HasFormActions;
use Filament\Resources\Form;

trait UsesResourceForm
{
    use HasFormActions;

    /**
     * @var \Filament\Resources\Form|null
     */
    protected $resourceForm;

    protected function getResourceForm(): Form
    {
        if (! $this->resourceForm) {
            $this->resourceForm = $this->form(Form::make()->columns(2));
        }

        return $this->resourceForm;
    }

    protected function form(Form $form): Form
    {
        return static::getResource()::form($form);
    }
}
