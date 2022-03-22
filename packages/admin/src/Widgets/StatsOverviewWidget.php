<?php

namespace Filament\Widgets;

class StatsOverviewWidget extends Widget
{
    /**
     * @var mixed[]|null
     */
    protected $cachedCards;

    /**
     * @var mixed[]|int|string
     */
    protected $columnSpan = 'full';

    /**
     * @var string
     */
    protected static $view = 'filament::widgets.stats-overview-widget';

    protected function getColumns(): int
    {
        switch ($count = count($this->getCachedCards())) {
            case 5:
            case 6:
            case 9:
            case 11:
                return 3;
            case 7:
            case 8:
            case 10:
            case 12:
                return 4;
            default:
                return $count;
        }
    }

    protected function getCachedCards(): array
    {
        return $this->cachedCards = $this->cachedCards ?? $this->getCards();
    }

    protected function getCards(): array
    {
        return [];
    }
}
