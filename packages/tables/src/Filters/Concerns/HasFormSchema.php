<?php

namespace Filament\Tables\Filters\Concerns;

use Closure;
use Filament\Forms\Components\Checkbox;

trait HasFormSchema
{
    /**
     * @var mixed[]|\Closure|null
     */
    protected $formSchema = null;

    /**
     * @param mixed[]|\Closure|null $schema
     * @return $this
     */
    public function form($schema)
    {
        $this->formSchema = $schema;

        return $this;
    }

    public function getFormSchema(): array
    {
        return $this->evaluate($this->formSchema) ?? [
            Checkbox::make('isActive')
                ->label($this->getLabel())
                ->default($this->getDefaultState()),
        ];
    }
}
