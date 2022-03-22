<?php

namespace Filament\Tables;

use Closure;
use Filament\Forms\ComponentContainer;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Traits\Tappable;
use Illuminate\View\Component as ViewComponent;

class Table extends ViewComponent implements Htmlable
{
    use Concerns\BelongsToLivewire;
    use Macroable;
    use Tappable;

    /**
     * @var \Illuminate\Contracts\View\View|null
     */
    protected $contentFooter;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var \Illuminate\Contracts\View\View|null
     */
    protected $emptyState;

    /**
     * @var string|null
     */
    protected $emptyStateDescription;

    /**
     * @var string|null
     */
    protected $emptyStateHeading;

    /**
     * @var string|null
     */
    protected $emptyStateIcon;

    /**
     * @var string|null
     */
    protected $filtersFormWidth;

    /**
     * @var string|null
     */
    protected $recordAction;

    /**
     * @var \Closure|null
     */
    protected $getRecordUrlUsing;

    /**
     * @var \Illuminate\Contracts\View\View|null
     */
    protected $header;

    /**
     * @var \Closure|string|null
     */
    protected $heading = null;

    /**
     * @var bool
     */
    protected $isPaginationEnabled = true;

    /**
     * @var mixed[]
     */
    protected $meta = [];

    /**
     * @var string
     */
    protected $model;

    /**
     * @var mixed[]|null
     */
    protected $recordsPerPageSelectOptions;

    final public function __construct(HasTable $livewire)
    {
        $this->livewire($livewire);
    }

    /**
     * @return $this
     */
    public static function make(HasTable $livewire)
    {
        return app(static::class, ['livewire' => $livewire]);
    }

    /**
     * @return $this
     */
    public function description(?string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return $this
     */
    public function emptyState(?View $view)
    {
        $this->emptyState = $view;

        return $this;
    }

    /**
     * @return $this
     */
    public function emptyStateDescription(?string $description)
    {
        $this->emptyStateDescription = $description;

        return $this;
    }

    /**
     * @return $this
     */
    public function emptyStateHeading(?string $heading)
    {
        $this->emptyStateHeading = $heading;

        return $this;
    }

    /**
     * @return $this
     */
    public function emptyStateIcon(?string $icon)
    {
        $this->emptyStateIcon = $icon;

        return $this;
    }

    /**
     * @return $this
     */
    public function enablePagination(bool $condition = true)
    {
        $this->isPaginationEnabled = $condition;

        return $this;
    }

    /**
     * @return $this
     */
    public function contentFooter(?View $view)
    {
        $this->contentFooter = $view;

        return $this;
    }

    /**
     * @return $this
     */
    public function filtersFormWidth(?string $width)
    {
        $this->filtersFormWidth = $width;

        return $this;
    }

    /**
     * @return $this
     */
    public function recordAction(?string $action)
    {
        $this->recordAction = $action;

        return $this;
    }

    /**
     * @return $this
     */
    public function getRecordUrlUsing(?Closure $callback)
    {
        $this->getRecordUrlUsing = $callback;

        return $this;
    }

    /**
     * @return $this
     */
    public function header(?View $view)
    {
        $this->header = $view;

        return $this;
    }

    /**
     * @param \Closure|string|null $heading
     * @return $this
     */
    public function heading($heading)
    {
        $this->heading = $heading;

        return $this;
    }

    /**
     * @return $this
     */
    public function model(string $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return $this
     */
    public function recordsPerPageSelectOptions(array $options)
    {
        $this->recordsPerPageSelectOptions = $options;

        return $this;
    }

    public function getActions(): array
    {
        return $this->getLivewire()->getCachedTableActions();
    }

    public function getAllRecordsCount(): int
    {
        return $this->getLivewire()->getAllTableRecordsCount();
    }

    public function getBulkActions(): array
    {
        return $this->getLivewire()->getCachedTableBulkActions();
    }

    public function getColumns(): array
    {
        return $this->getLivewire()->getCachedTableColumns();
    }

    public function getContentFooter(): ?View
    {
        return $this->contentFooter;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getEmptyState(): ?View
    {
        return $this->emptyState;
    }

    public function getEmptyStateActions(): array
    {
        return $this->getLivewire()->getCachedTableEmptyStateActions();
    }

    public function getEmptyStateDescription(): ?string
    {
        return $this->emptyStateDescription;
    }

    public function getEmptyStateHeading(): string
    {
        return $this->emptyStateHeading ?? __('tables::table.empty.heading');
    }

    public function getEmptyStateIcon(): string
    {
        return $this->emptyStateIcon ?? 'heroicon-o-x';
    }

    public function getFilters(): array
    {
        return $this->getLivewire()->getCachedTableFilters();
    }

    public function getFiltersForm(): ComponentContainer
    {
        return $this->getLivewire()->getTableFiltersForm();
    }

    public function getFiltersFormWidth(): ?string
    {
        return $this->filtersFormWidth;
    }

    public function getHeader(): ?View
    {
        return $this->header;
    }

    public function getHeaderActions(): array
    {
        return $this->getLivewire()->getCachedTableHeaderActions();
    }

    public function getHeading(): ?string
    {
        return value($this->heading);
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getMountedAction(): ?Action
    {
        return $this->getLivewire()->getMountedTableAction();
    }

    public function getMountedActionForm(): ComponentContainer
    {
        return $this->getLivewire()->getMountedTableActionForm();
    }

    public function getMountedBulkAction(): ?BulkAction
    {
        return $this->getLivewire()->getMountedTableBulkAction();
    }

    public function getMountedBulkActionForm(): ComponentContainer
    {
        return $this->getLivewire()->getMountedTableBulkActionForm();
    }

    /**
     * @return \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getRecords()
    {
        return $this->getLivewire()->getTableRecords();
    }

    public function getRecordsPerPageSelectOptions(): array
    {
        return $this->recordsPerPageSelectOptions;
    }

    public function getRecordAction(): ?string
    {
        return $this->recordAction;
    }

    public function getRecordUrl(Model $record): ?string
    {
        $callback = $this->getRecordUrlUsing;

        if (! $callback) {
            return null;
        }

        return $callback($record);
    }

    public function getSortColumn(): ?string
    {
        return $this->getLivewire()->getTableSortColumn();
    }

    public function getSortDirection(): ?string
    {
        return $this->getLivewire()->getTableSortDirection();
    }

    public function isFilterable(): bool
    {
        return $this->getLivewire()->isTableFilterable();
    }

    public function isPaginationEnabled(): bool
    {
        return $this->isPaginationEnabled;
    }

    public function isSelectionEnabled(): bool
    {
        return $this->getLivewire()->isTableSelectionEnabled();
    }

    public function isSearchable(): bool
    {
        return $this->getLivewire()->isTableSearchable();
    }

    public function toHtml(): string
    {
        return $this->render()->render();
    }

    public function render(): View
    {
        return view('tables::index', array_merge($this->data(), [
            'table' => $this,
        ]));
    }
}
