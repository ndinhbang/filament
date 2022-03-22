<?php

namespace Filament\Forms\Components;

class Fieldset extends Component
{
    /**
     * @var string
     */
    protected $view = 'forms::components.fieldset';

    final public function __construct(string $label)
    {
        $this->label($label);
    }

    /**
     * @return $this
     */
    public static function make(string $label)
    {
        $static = app(static::class, ['label' => $label]);
        $static->setUp();

        return $static;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->columnSpan('full');

        $this->columns(2);
    }
}
