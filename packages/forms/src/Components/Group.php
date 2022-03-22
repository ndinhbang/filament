<?php

namespace Filament\Forms\Components;

class Group extends Component
{
    /**
     * @var string
     */
    protected $view = 'forms::components.group';

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
}
