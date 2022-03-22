<?php

namespace Filament\Forms\Components;

use Closure;

class RichEditor extends Field implements Contracts\HasFileAttachments
{
    use Concerns\HasExtraAlpineAttributes;
    use Concerns\HasFileAttachments;
    use Concerns\HasPlaceholder;
    use Concerns\InteractsWithToolbarButtons;

    /**
     * @var string
     */
    protected $view = 'forms::components.rich-editor';

    /**
     * @var mixed[]|\Closure
     */
    protected $toolbarButtons = [
        'attachFiles',
        'blockquote',
        'bold',
        'bulletList',
        'codeBlock',
        'h2',
        'h3',
        'italic',
        'link',
        'orderedList',
        'redo',
        'strike',
        'undo',
    ];
}
