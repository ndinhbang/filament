<?php

namespace Filament\Forms\Components;

use Closure;
use Illuminate\Contracts\Support\Arrayable;

class TagsInput extends Field
{
    use Concerns\HasExtraAlpineAttributes;
    use Concerns\HasExtraInputAttributes;
    use Concerns\HasPlaceholder;

    /**
     * @var string
     */
    protected $view = 'forms::components.tags-input';

    /**
     * @var \Closure|string|null
     */
    protected $separator = null;

    /**
     * @var mixed[]|\Closure|\Illuminate\Contracts\Support\Arrayable|null
     */
    protected $suggestions = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->default([]);

        $this->afterStateHydrated(function (TagsInput $component, $state): void {
            if (is_array($state)) {
                return;
            }

            if (! ($separator = $component->getSeparator())) {
                $component->state([]);

                return;
            }

            $state = explode($separator, $state);

            if (count($state) === 1 && blank($state[0])) {
                $state = [];
            }

            $component->state($state);
        });

        $this->dehydrateStateUsing(function (TagsInput $component, $state) {
            if ($separator = $component->getSeparator()) {
                return implode($separator, $state);
            }

            return $state;
        });

        $this->placeholder(__('forms::components.tags_input.placeholder'));
    }

    /**
     * @param \Closure|string|null $separator
     * @return $this
     */
    public function separator($separator = ',')
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * @param mixed[]|\Closure|\Illuminate\Contracts\Support\Arrayable $suggestions
     * @return $this
     */
    public function suggestions($suggestions)
    {
        $this->suggestions = $suggestions;

        return $this;
    }

    public function getSeparator(): ?string
    {
        return $this->evaluate($this->separator);
    }

    public function getSuggestions(): array
    {
        $suggestions = $this->evaluate($this->suggestions ?? []);

        if ($suggestions instanceof Arrayable) {
            $suggestions = $suggestions->toArray();
        }

        return $suggestions;
    }
}
