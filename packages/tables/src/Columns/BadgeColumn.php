<?php

namespace Filament\Tables\Columns;

class BadgeColumn extends TextColumn
{
    use Concerns\CanFormatState;
    use Concerns\HasColors;

    /**
     * @var string
     */
    protected $view = 'tables::columns.badge-column';
}
