<?php

namespace Filament\Tables\Actions\Modal\Actions\Concerns;

trait HasAction
{
    /**
     * @var string|null
     */
    protected $action;

    /**
     * @param string|null $action
     * @return $this
     */
    public function action($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAction()
    {
        return $this->action;
    }
}
