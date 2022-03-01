<?php

namespace Filament\Tables\Actions\Concerns;

use Closure;

trait HasFormSchema
{
    /**
     * @var mixed[]|\Closure
     */
    protected $formSchema = [];

    /**
     * @param mixed[]|\Closure $schema
     * @return $this
     */
    public function form($schema)
    {
        $this->formSchema = $schema;

        return $this;
    }

    public function getFormSchema(): array
    {
        return $this->evaluate($this->formSchema);
    }

    public function hasFormSchema(): bool
    {
        return (bool) count($this->getFormSchema());
    }
}
