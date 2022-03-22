<?php

namespace Filament\Tables\Actions\Concerns;

use Closure;
use Filament\Forms\ComponentContainer;

trait CanBeMounted
{
    /**
     * @var \Closure|null
     */
    protected $mountUsing;

    /**
     * @return $this
     */
    public function mountUsing(?Closure $callback)
    {
        $this->mountUsing = $callback;

        return $this;
    }

    public function getMountUsing(): Closure
    {
        return $this->mountUsing ?? function ($action, ?ComponentContainer $form = null): void {
            if (! $action->shouldOpenModal()) {
                return;
            }

            if (! $form) {
                return;
            }

            $form->fill();
        };
    }
}
