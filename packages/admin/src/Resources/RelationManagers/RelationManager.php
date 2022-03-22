<?php

namespace Filament\Resources\RelationManagers;

use Filament\Facades\Filament;
use Filament\Http\Livewire\Concerns\CanNotify;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Component;

class RelationManager extends Component implements Tables\Contracts\HasTable
{
    use CanNotify;
    use Tables\Concerns\InteractsWithTable;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $ownerRecord;

    /**
     * @var string|null
     */
    protected static $recordTitleAttribute;

    /**
     * @var string
     */
    protected static $relationship;

    /**
     * @var string|null
     */
    protected static $inverseRelationship;

    /**
     * @var \Filament\Resources\Form|null
     */
    protected $resourceForm;

    /**
     * @var \Filament\Resources\Table|null
     */
    protected $resourceTable;

    /**
     * @var string|null
     */
    protected static $label;

    /**
     * @var string|null
     */
    protected static $pluralLabel;

    /**
     * @var string|null
     */
    protected static $title;

    /**
     * @var string
     */
    protected static $view;

    protected function getTableQueryStringIdentifier(): ?string
    {
        return lcfirst(class_basename(static::class));
    }

    protected function getResourceForm(): Form
    {
        if (! $this->resourceForm) {
            $this->resourceForm = static::form(Form::make()->columns(2));
        }

        return $this->resourceForm;
    }

    protected function callHook(string $hook): void
    {
        if (! method_exists($this, $hook)) {
            return;
        }

        $this->{$hook}();
    }

    protected function can(string $action, ?Model $record = null): bool
    {
        $policy = Gate::getPolicyFor($model = $this->getRelatedModel());

        if ($policy === null || (! method_exists($policy, $action))) {
            return true;
        }

        return Gate::forUser(Filament::auth()->user())->check($action, $record ?? $model);
    }

    public static function canViewForRecord(Model $ownerRecord): bool
    {
        $model = get_class($ownerRecord->{static::getRelationshipName()}()->getQuery()->getModel());

        $policy = Gate::getPolicyFor($model);
        $action = 'viewAny';

        if ($policy === null || (! method_exists($policy, $action))) {
            return true;
        }

        return Gate::forUser(Filament::auth()->user())->check($action, $model);
    }

    public static function form(Form $form): Form
    {
        return $form;
    }

    public function getInverseRelationshipName(): string
    {
        return static::$inverseRelationship ?? (string) Str::of(class_basename($this->ownerRecord))
            ->lower()
            ->plural()
            ->camel();
    }

    public static function table(Table $table): Table
    {
        return $table;
    }

    public static function getRelationshipName(): string
    {
        return static::$relationship;
    }

    public static function getTitle(): string
    {
        return static::$title ?? Str::title(static::getPluralRecordLabel());
    }

    public static function getRecordTitleAttribute(): ?string
    {
        return static::$recordTitleAttribute;
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        return (($record2 = $record) ? $record2->getAttribute(static::getRecordTitleAttribute()) : null) ?? (($record2 = $record) ? $record2->getKey() : null);
    }

    protected static function getRecordLabel(): string
    {
        return static::$label ?? Str::singular(static::getPluralRecordLabel());
    }

    protected static function getPluralRecordLabel(): string
    {
        return static::$pluralLabel ?? (string) Str::of(static::getRelationshipName())
            ->kebab()
            ->replace('-', ' ');
    }

    protected function getRelatedModel(): string
    {
        return get_class($this->getRelationship()->getQuery()->getModel());
    }

    protected function getRelationship(): Relation
    {
        return $this->ownerRecord->{static::getRelationshipName()}();
    }

    protected function getResourceTable(): Table
    {
        if (! $this->resourceTable) {
            $this->resourceTable = Table::make();
        }

        return $this->resourceTable;
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return $this->getResourceTable()->getDefaultSortColumn();
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return $this->getResourceTable()->getDefaultSortDirection();
    }

    protected function getTableActions(): array
    {
        return $this->getResourceTable()->getActions();
    }

    protected function getTableBulkActions(): array
    {
        return $this->getResourceTable()->getBulkActions();
    }

    protected function getTableColumns(): array
    {
        return $this->getResourceTable()->getColumns();
    }

    protected function getTableFilters(): array
    {
        return $this->getResourceTable()->getFilters();
    }

    protected function getTableHeaderActions(): array
    {
        return $this->getResourceTable()->getHeaderActions();
    }

    protected function getTableHeading(): ?string
    {
        return static::getTitle();
    }

    protected function getTableQuery(): Builder
    {
        return $this->getRelationship()->getQuery();
    }

    public function render(): View
    {
        return view(static::$view, $this->getViewData());
    }

    protected function getViewData(): array
    {
        return [];
    }
}
