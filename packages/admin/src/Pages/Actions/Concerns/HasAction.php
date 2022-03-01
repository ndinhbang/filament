<?php

namespace Filament\Pages\Actions\Concerns;

use Closure;

trait HasAction
{
    /**
     * @var \Closure|string|null
     */
    protected $action = null;

    /**
     * @param \Closure|string|null $action
     * @return $this
     */
    public function action($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return \Closure|string|null
     */
    public function getAction()
    {
        return $this->action;
    }
}
