<?php

namespace Filament\Tables\Actions\Modal\Actions;

class ButtonAction extends Action
{
    use Concerns\CanBeOutlined;
    use Concerns\HasIcon;

    /**
     * @var string
     */
    protected $view = 'tables::actions.modal.actions.button-action';

    /**
     * @var string|null
     */
    protected $iconPosition;

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
