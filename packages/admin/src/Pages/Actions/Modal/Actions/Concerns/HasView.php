<?php

namespace Filament\Pages\Actions\Modal\Actions\Concerns;

trait HasView
{
    protected string $view;

    /**
     * @return $this
     */
    public function view(string $view)
    {
        $this->view = $view;

        return $this;
    }

    public function getView(): string
    {
        return $this->view;
    }
}
