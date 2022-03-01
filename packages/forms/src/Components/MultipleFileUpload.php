<?php

namespace Filament\Forms\Components;

use Closure;

/**
 * @deprecated Use `\Filament\Forms\Components\FileUpload` instead, with the `multiple()` method.
 */
class MultipleFileUpload extends Field
{
    protected string $view = 'forms::components.multiple-file-upload';

    /**
     * @var \Closure|int|null
     */
    protected $maxFiles = null;

    /**
     * @var \Closure|int|null
     */
    protected $minFiles = null;

    /**
     * @var \Closure|\Filament\Forms\Components\BaseFileUpload|null
     */
    protected $uploadComponent = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrateStateUsing(function (array $state): array {
            return array_values($state);
        });
    }

    /**
     * @param \Closure|int|null $count
     * @return $this
     */
    public function maxFiles($count)
    {
        $this->maxFiles = $count;

        return $this;
    }

    /**
     * @param \Closure|int|null $count
     * @return $this
     */
    public function minFiles($count)
    {
        $this->minFiles = $count;

        return $this;
    }

    /**
     * @param \Closure|\Filament\Forms\Components\Component|null $component
     * @return $this
     */
    public function uploadComponent($component)
    {
        $this->uploadComponent = $component;

        return $this;
    }

    public function getChildComponents(): array
    {
        return [$this->getUploadComponent()];
    }

    public function getUploadComponent(): Component
    {
        $component = $this->evaluate($this->uploadComponent) ?? $this->getDefaultUploadComponent();

        if (filled($this->maxFiles)) {
            $component->maxFiles($this->maxFiles);
        }

        if (filled($this->minFiles)) {
            $component->minFiles($this->minFiles);
        }

        return $component
            ->label($this->getLabel())
            ->multiple()
            ->statePath(null)
            ->validationAttribute($this->getValidationAttribute());
    }

    protected function getDefaultUploadComponent(): BaseFileUpload
    {
        return FileUpload::make('files');
    }
}
