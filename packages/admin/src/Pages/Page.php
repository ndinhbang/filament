<?php

namespace Filament\Pages;

use Closure;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Http\Livewire\Concerns\CanNotify;
use Filament\Navigation\NavigationItem;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Livewire\Component;

class Page extends Component implements Forms\Contracts\HasForms
{
    use CanNotify;
    use Concerns\HasActions;
    use Forms\Concerns\InteractsWithForms;

    /**
     * @var string
     */
    protected static $layout = 'filament::components.layouts.app';

    /**
     * @var string|null
     */
    protected static $navigationGroup;

    /**
     * @var string|null
     */
    protected static $navigationIcon;

    /**
     * @var string|null
     */
    protected static $navigationLabel;

    /**
     * @var int|null
     */
    protected static $navigationSort;

    /**
     * @var bool
     */
    protected static $shouldRegisterNavigation = true;

    /**
     * @var string|null
     */
    protected static $slug;

    /**
     * @var string|null
     */
    protected static $title;

    /**
     * @var string
     */
    protected static $view;

    public static function registerNavigationItems(): void
    {
        if (! static::shouldRegisterNavigation()) {
            return;
        }

        Filament::registerNavigationItems(static::getNavigationItems());
    }

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make()
                ->group(static::getNavigationGroup())
                ->icon(static::getNavigationIcon())
                ->isActiveWhen(function () : bool {
                    return request()->routeIs(static::getRouteName());
                })
                ->label(static::getNavigationLabel())
                ->sort(static::getNavigationSort())
                ->url(static::getNavigationUrl()),
        ];
    }

    public static function getRouteName(): string
    {
        $slug = static::getSlug();

        return "filament.pages.{$slug}";
    }

    public static function getRoutes(): Closure
    {
        return function () {
            $slug = static::getSlug();

            Route::get($slug, static::class)->name($slug);
        };
    }

    public static function getSlug(): string
    {
        return static::$slug ?? Str::kebab(static::$title ?? class_basename(static::class));
    }

    public static function getUrl(array $parameters = [], bool $absolute = true): string
    {
        return route(static::getRouteName(), $parameters, $absolute);
    }

    public function render(): View
    {
        return view(static::$view, $this->getViewData())
            ->layout(static::$layout, $this->getLayoutData());
    }

    protected function getBreadcrumbs(): array
    {
        return [];
    }

    protected static function getNavigationGroup(): ?string
    {
        return static::$navigationGroup;
    }

    protected static function getNavigationIcon(): string
    {
        return static::$navigationIcon ?? 'heroicon-o-document-text';
    }

    protected static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? static::$title ?? Str::of(class_basename(static::class))
            ->kebab()
            ->replace('-', ' ')
            ->title();
    }

    protected static function getNavigationSort(): ?int
    {
        return static::$navigationSort;
    }

    protected static function getNavigationUrl(): string
    {
        return static::getUrl();
    }

    /**
     * @return mixed[]|\Illuminate\Contracts\View\View|null
     */
    protected function getActions()
    {
        return null;
    }

    protected function getFooter(): ?View
    {
        return null;
    }

    protected function getHeader(): ?View
    {
        return null;
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    protected function getFooterWidgets(): array
    {
        return [];
    }

    protected function getHeading(): string
    {
        return $this->getTitle();
    }

    protected function getTitle(): string
    {
        return static::$title ?? (string) Str::of(class_basename(static::class))
            ->kebab()
            ->replace('-', ' ')
            ->title();
    }

    protected function getLayoutData(): array
    {
        return [
            'breadcrumbs' => $this->getBreadcrumbs(),
            'title' => $this->getTitle(),
        ];
    }

    protected function getViewData(): array
    {
        return [];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return static::$shouldRegisterNavigation;
    }

    protected function getForms(): array
    {
        return [
            'mountedActionForm' => $this->makeForm()
                ->schema(($action = $this->getMountedAction()) ? $action->getFormSchema() : [])
                ->statePath('mountedActionData')
                ->model($this->getMountedActionFormModel()),
        ];
    }
}
