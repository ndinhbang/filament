<?php

namespace Filament\Forms\Components;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\HtmlString;

class MultiSelect extends Field
{
    use Concerns\HasExtraAlpineAttributes;
    use Concerns\HasOptions;
    use Concerns\HasPlaceholder;

    /**
     * @var string
     */
    protected $view = 'forms::components.multi-select';

    /**
     * @var \Closure|null
     */
    protected $getOptionLabelsUsing;

    /**
     * @var \Closure|null
     */
    protected $getSearchResultsUsing;

    /**
     * @var \Closure|\Illuminate\Support\HtmlString|string|null
     */
    protected $noSearchResultsMessage = null;

    /**
     * @var \Closure|\Illuminate\Support\HtmlString|string|null
     */
    protected $searchPrompt = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->default([]);

        $this->afterStateHydrated(function (MultiSelect $component, $state) {
            if (is_array($state)) {
                return;
            }

            $component->state([]);
        });

        $this->getOptionLabelsUsing(function (MultiSelect $component, array $values): array {
            $options = $component->getOptions();

            return collect($values)
                ->mapWithKeys(function ($value) use ($options) {
                    return [$value => $options[$value] ?? $value];
                })
                ->toArray();
        });

        $this->noSearchResultsMessage(__('forms::components.multi_select.no_search_results_message'));

        $this->placeholder(__('forms::components.multi_select.placeholder'));

        $this->searchPrompt(__('forms::components.multi_select.search_prompt'));
    }

    /**
     * @return $this
     */
    public function getOptionLabelsUsing(?Closure $callback)
    {
        $this->getOptionLabelsUsing = $callback;

        return $this;
    }

    /**
     * @return $this
     */
    public function getSearchResultsUsing(?Closure $callback)
    {
        $this->getSearchResultsUsing = $callback;

        return $this;
    }

    /**
     * @param \Closure|\Illuminate\Support\HtmlString|string|null $message
     * @return $this
     */
    public function noSearchResultsMessage($message)
    {
        $this->noSearchResultsMessage = $message;

        return $this;
    }

    /**
     * @param \Closure|\Illuminate\Support\HtmlString|string|null $message
     * @return $this
     */
    public function searchPrompt($message)
    {
        $this->searchPrompt = $message;

        return $this;
    }

    public function getOptionLabels(): array
    {
        $labels = $this->evaluate($this->getOptionLabelsUsing, [
            'values' => $this->getState(),
        ]);

        if ($labels instanceof Arrayable) {
            $labels = $labels->toArray();
        }

        return $labels;
    }

    /**
     * @return \Illuminate\Support\HtmlString|string
     */
    public function getNoSearchResultsMessage()
    {
        return $this->evaluate($this->noSearchResultsMessage);
    }

    /**
     * @return \Illuminate\Support\HtmlString|string
     */
    public function getSearchPrompt()
    {
        return $this->evaluate($this->searchPrompt);
    }

    public function getSearchResults(string $query): array
    {
        if (! $this->getSearchResultsUsing) {
            return [];
        }

        $results = $this->evaluate($this->getSearchResultsUsing, [
            'query' => $query,
        ]);

        if ($results instanceof Arrayable) {
            $results = $results->toArray();
        }

        return $results;
    }
}
