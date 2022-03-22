<?php

namespace Filament\Forms\Components;

use Closure;

class MarkdownEditor extends Field implements Contracts\HasFileAttachments
{
    use Concerns\HasExtraAlpineAttributes;
    use Concerns\HasFileAttachments;
    use Concerns\HasPlaceholder;
    use Concerns\InteractsWithToolbarButtons;

    /**
     * @var string
     */
    protected $view = 'forms::components.markdown-editor';

    /**
     * @var mixed[]|\Closure
     */
    protected $toolbarButtons = [
        'attachFiles',
        'bold',
        'bulletList',
        'codeBlock',
        'edit',
        'italic',
        'link',
        'orderedList',
        'preview',
        'strike',
    ];
}
