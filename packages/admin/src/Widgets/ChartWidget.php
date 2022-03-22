<?php

namespace Filament\Widgets;

class ChartWidget extends Widget
{
    /**
     * @var mixed[]|null
     */
    protected $cachedData;

    /**
     * @var string
     */
    public $dataChecksum;

    /**
     * @var string|null
     */
    public $filter;

    /**
     * @var string|null
     */
    protected static $heading;

    /**
     * @var mixed[]|null
     */
    protected static $options;

    /**
     * @var string|null
     */
    protected static $pollingInterval = '5s';

    /**
     * @var string
     */
    protected static $view = 'filament::widgets.chart-widget';

    public function mount()
    {
        $this->dataChecksum = $this->generateDataChecksum();
    }

    protected function generateDataChecksum(): string
    {
        return md5(json_encode($this->getCachedData()));
    }

    protected function getCachedData(): array
    {
        return $this->cachedData = $this->cachedData ?? $this->getData();
    }

    protected function getData(): array
    {
        return [];
    }

    protected function getFilters(): ?array
    {
        return null;
    }

    protected function getHeading(): ?string
    {
        return static::$heading;
    }

    protected function getOptions(): ?array
    {
        return static::$options;
    }

    protected function getPollingInterval(): ?string
    {
        return static::$pollingInterval;
    }

    public function updateChartData()
    {
        $newDataChecksum = $this->generateDataChecksum();

        if ($newDataChecksum !== $this->dataChecksum) {
            $this->dataChecksum = $newDataChecksum;

            $this->emitSelf('updateChartData', [
                'data' => $this->getCachedData(),
            ]);
        }
    }

    public function updatedFilter(): void
    {
        $newDataChecksum = $this->generateDataChecksum();

        if ($newDataChecksum !== $this->dataChecksum) {
            $this->dataChecksum = $newDataChecksum;

            $this->emitSelf('filterChartData', [
                'data' => $this->getCachedData(),
            ]);
        }
    }
}
