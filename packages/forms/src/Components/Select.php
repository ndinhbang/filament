<?php

namespace Filament\Forms\Components;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\HtmlString;

class Select extends Field
{
    use Concerns\HasAffixes;
    use Concerns\HasExtraAlpineAttributes;
    use Concerns\HasOptions;
    use Concerns\HasPlaceholder;

    protected string $view = 'forms::components.select';

    protected ?Closure $getOptionLabelUsing = null;

    protected ?Closure $getSearchResultsUsing = null;

    /**
     * @var bool|\Closure|null
     */
    protected $isOptionDisabled = null;

    /**
     * @var bool|\Closure|null
     */
    protected $isPlaceholderSelectionDisabled = false;

    /**
     * @var bool|\Closure
     */
    protected $isSearchable = false;

    protected ?array $searchColumns = null;

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

        $this->getOptionLabelUsing(function (Select $component, $value): ?string {
            if (array_key_exists($value, $options = $component->getOptions())) {
                return $options[$value];
            }

            return $value;
        });

        $this->noSearchResultsMessage(__('forms::components.select.no_search_results_message'));

        $this->placeholder(__('forms::components.select.placeholder'));

        $this->searchPrompt(__('forms::components.select.search_prompt'));
    }

    /**
     * @return $this
     */
    public function boolean(string $trueLabel = 'Yes', string $falseLabel = 'No')
    {
        $this->options([
            1 => $trueLabel,
            0 => $falseLabel,
        ]);

        return $this;
    }

    /**
     * @param bool|\Closure $callback
     * @return $this
     */
    public function disableOptionWhen($callback)
    {
        $this->isOptionDisabled = $callback;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function disablePlaceholderSelection($condition = true)
    {
        $this->isPlaceholderSelectionDisabled = $condition;

        return $this;
    }

    /**
     * @return $this
     */
    public function getOptionLabelUsing(?Closure $callback)
    {
        $this->getOptionLabelUsing = $callback;

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
     * @param mixed[]|bool|\Closure $condition
     * @return $this
     */
    public function searchable($condition = true)
    {
        if (is_array($condition)) {
            $this->isSearchable = true;
            $this->searchColumns = $condition;
        } else {
            $this->isSearchable = $condition;
            $this->searchColumns = null;
        }

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

    public function getOptionLabel(): ?string
    {
        return $this->evaluate($this->getOptionLabelUsing, [
            'value' => $this->getState(),
        ]);
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

    public function getSearchColumns(): ?array
    {
        return $this->searchColumns;
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

    public function isOptionDisabled($value, string $label): bool
    {
        if ($this->isOptionDisabled === null) {
            return false;
        }

        return (bool) $this->evaluate($this->isOptionDisabled, [
            'label' => $label,
            'value' => $value,
        ]);
    }

    public function isPlaceholderSelectionDisabled(): bool
    {
        return (bool) $this->evaluate($this->isPlaceholderSelectionDisabled);
    }

    public function isSearchable(): bool
    {
        return (bool) $this->evaluate($this->isSearchable);
    }
}
