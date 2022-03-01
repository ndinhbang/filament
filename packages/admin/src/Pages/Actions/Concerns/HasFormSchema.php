<?php

namespace Filament\Pages\Actions\Concerns;

trait HasFormSchema
{
    protected array $formSchema = [];

    /**
     * @return $this
     */
    public function form(array $schema)
    {
        $this->formSchema = $schema;

        return $this;
    }

    public function getFormSchema(): array
    {
        return $this->formSchema;
    }

    public function hasFormSchema(): bool
    {
        return (bool) count($this->getFormSchema());
    }
}
