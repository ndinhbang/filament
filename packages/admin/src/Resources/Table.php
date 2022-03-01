<?php

namespace Filament\Resources;

class Table
{
    protected array $actions = [];

    protected array $bulkActions = [];

    protected array $columns = [];

    protected ?string $defaultSortColumn = null;

    protected ?string $defaultSortDirection = null;

    protected array $filters = [];

    protected array $headerActions = [];

    final public function __construct()
    {
    }

    /**
     * @return $this
     */
    public static function make()
    {
        return app(static::class);
    }

    /**
     * @return $this
     */
    public function actions(array $actions)
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * @return $this
     */
    public function bulkActions(array $actions)
    {
        $this->bulkActions = $actions;

        return $this;
    }

    /**
     * @return $this
     */
    public function columns(array $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @return $this
     */
    public function defaultSort(string $column, string $direction = 'asc')
    {
        $this->defaultSortColumn = $column;
        $this->defaultSortDirection = $direction;

        return $this;
    }

    /**
     * @return $this
     */
    public function filters(array $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @return $this
     */
    public function headerActions(array $actions)
    {
        $this->headerActions = $actions;

        return $this;
    }

    /**
     * @return $this
     */
    public function prependActions(array $actions)
    {
        $this->actions = array_merge($actions, $this->actions);

        return $this;
    }

    /**
     * @return $this
     */
    public function prependBulkActions(array $actions)
    {
        $this->bulkActions = array_merge($actions, $this->bulkActions);

        return $this;
    }

    /**
     * @return $this
     */
    public function prependHeaderActions(array $actions)
    {
        $this->headerActions = array_merge($actions, $this->headerActions);

        return $this;
    }

    /**
     * @return $this
     */
    public function pushActions(array $actions)
    {
        $this->actions = array_merge($this->actions, $actions);

        return $this;
    }

    /**
     * @return $this
     */
    public function pushBulkActions(array $actions)
    {
        $this->bulkActions = array_merge($this->bulkActions, $actions);

        return $this;
    }

    /**
     * @return $this
     */
    public function pushHeaderActions(array $actions)
    {
        $this->headerActions = array_merge($this->headerActions, $actions);

        return $this;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function getBulkActions(): array
    {
        return $this->bulkActions;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getDefaultSortColumn(): ?string
    {
        return $this->defaultSortColumn;
    }

    public function getDefaultSortDirection(): ?string
    {
        return $this->defaultSortDirection;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getHeaderActions(): array
    {
        return $this->headerActions;
    }
}
