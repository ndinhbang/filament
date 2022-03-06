<x-tables::icon-button
    icon="heroicon-o-filter"
    x-on:click="isOpen = ! isOpen"
    :label="__('tables::table.buttons.filter.label')"
    class="{{ \Illuminate\Support\Arr::toCssClasses(['filament-tables-filters-trigger']) }}"
/>
