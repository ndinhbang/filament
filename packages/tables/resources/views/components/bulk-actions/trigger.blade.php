<x-tables::icon-button
    icon="heroicon-o-dots-vertical"
    x-on:click="isOpen = ! isOpen"
    :label="__('tables::table.buttons.open_actions.label')"
    class="{{ \Illuminate\Support\Arr::toCssClasses(['filament-tables-bulk-actions-trigger']) }}"
/>
