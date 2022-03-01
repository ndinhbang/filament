<?php

namespace Filament\Forms\Components;

class Card extends Component
{
    protected string $view = 'forms::components.card';

    final public function __construct(array $schema = [])
    {
        $this->schema($schema);
    }

    /**
     * @return $this
     */
    public static function make(array $schema = [])
    {
        $static = app(static::class, ['schema' => $schema]);
        $static->setUp();

        return $static;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->columnSpan('full');
    }
}
