<?php

namespace Filament\Pages;

use Closure;
use Illuminate\Support\Facades\Route;

class Dashboard extends Page
{
    /**
     * @var string|null
     */
    protected static $navigationIcon = 'heroicon-o-home';

    /**
     * @var int|null
     */
    protected static $navigationSort = -2;

    /**
     * @var string
     */
    protected static $view = 'filament::pages.dashboard';

    protected static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? static::$title ?? __('filament::pages/dashboard.title');
    }

    public static function getRoutes(): Closure
    {
        return function () {
            Route::get('/', static::class)->name(static::getSlug());
        };
    }

    protected function getTitle(): string
    {
        return static::$title ?? __('filament::pages/dashboard.title');
    }
}
