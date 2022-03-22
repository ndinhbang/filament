<?php

namespace Filament\Tables\Columns;

use Closure;

class TagsColumn extends Column
{
    /**
     * @var string
     */
    protected $view = 'tables::columns.tags-column';

    /**
     * @var \Closure|string|null
     */
    protected $separator = null;

    public function getTags(): array
    {
        $tags = $this->getState();

        if (is_array($tags)) {
            return $tags;
        }

        if (! ($separator = $this->getSeparator())) {
            return [];
        }

        $tags = explode($separator, $tags);

        if (count($tags) === 1 && blank($tags[0])) {
            $tags = [];
        }

        return $tags;
    }

    /**
     * @param \Closure|string|null $separator
     * @return $this
     */
    public function separator($separator = ',')
    {
        $this->separator = $separator;

        return $this;
    }

    public function getSeparator(): ?string
    {
        return $this->evaluate($this->separator);
    }
}
