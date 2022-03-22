<?php

namespace Filament\Forms\Components;

class Grid extends Component
{
    /**
     * @var string
     */
    protected $view = 'forms::components.grid';

    /**
     * @param mixed[]|int|null $columns
     */
    final public function __construct($columns)
    {
        $this->columns($columns);
    }

    /**
     * @param mixed[]|int|null $columns
     * @return $this
     */
    public static function make($columns = 2)
    {
        $static = app(static::class, ['columns' => $columns]);
        $static->setUp();

        return $static;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->columnSpan('full');
    }
}
