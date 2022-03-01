<?php

namespace Filament\Tables\Actions\Modal\Actions;

class ButtonAction extends Action
{
    use Concerns\CanBeOutlined;
    use Concerns\HasIcon;

    protected string $view = 'tables::actions.modal.actions.button-action';

    protected ?string $iconPosition = null;

    /**
     * @return $this
     */
    public function iconPosition(string $position)
    {
        $this->iconPosition = $position;

        return $this;
    }

    public function getIconPosition(): ?string
    {
        return $this->iconPosition;
    }
}
