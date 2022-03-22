<?php

namespace Filament\Forms\Components;

use Closure;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\TemporaryUploadedFile;

class BaseFileUpload extends Field
{
    /**
     * @var mixed[]|\Closure|\Illuminate\Contracts\Support\Arrayable|null
     */
    protected $acceptedFileTypes = null;

    /**
     * @var bool|\Closure
     */
    protected $canReorder = false;

    /**
     * @var bool|\Closure
     */
    protected $canPreview = true;

    /**
     * @var \Closure|string|null
     */
    protected $directory = null;

    /**
     * @var \Closure|string|null
     */
    protected $diskName = null;

    /**
     * @var bool|\Closure
     */
    protected $isMultiple = false;

    /**
     * @var \Closure|int|null
     */
    protected $maxSize = null;

    /**
     * @var \Closure|int|null
     */
    protected $minSize = null;

    /**
     * @var \Closure|int|null
     */
    protected $maxFiles = null;

    /**
     * @var \Closure|int|null
     */
    protected $minFiles = null;

    /**
     * @var bool|\Closure
     */
    protected $shouldPreserveFilenames = false;

    /**
     * @var \Closure|string
     */
    protected $visibility = 'public';

    /**
     * @var \Closure|null
     */
    protected $deleteUploadedFileUsing;

    /**
     * @var \Closure|null
     */
    protected $getUploadedFileUrlUsing;

    /**
     * @var \Closure|null
     */
    protected $reorderUploadedFilesUsing;

    /**
     * @var \Closure|null
     */
    protected $saveUploadedFileUsing;

    protected function setUp(): void
    {
        parent::setUp();

        $this->afterStateHydrated(function (BaseFileUpload $component, $state): void {
            if (blank($state)) {
                $component->state([]);

                return;
            }

            $files = collect(Arr::wrap($state))
                ->mapWithKeys(function (string $file) : array {
                    return [(string) Str::uuid() => $file];
                })
                ->toArray();

            $component->state($files);
        });

        $this->afterStateUpdated(function (BaseFileUpload $component, $state) {
            if (blank($state)) {
                return;
            }

            if (is_array($state)) {
                return;
            }

            $component->state([(string) Str::uuid() => $state]);
        });

        $this->beforeStateDehydrated(function (BaseFileUpload $component): void {
            $component->saveUploadedFiles();
        });

        $this->dehydrateStateUsing(function (BaseFileUpload $component, ?array $state) {
            $files = array_values($state ?? []);

            if ($component->isMultiple()) {
                return $files;
            }

            return $files[0] ?? null;
        });

        $this->getUploadedFileUrlUsing(function (BaseFileUpload $component, string $file): string {
            /** @var FilesystemAdapter $storage */
            $storage = $component->getDisk();

            // An ugly mess as we need to support both Flysystem v1 and v3.
            $storageAdapter = method_exists($storage, 'getAdapter') ? $storage->getAdapter() : (method_exists($storageDriver = $storage->getDriver(), 'getAdapter') ? $storageDriver->getAdapter() : null);
            $supportsTemporaryUrls = method_exists($storageAdapter, 'temporaryUrl') || method_exists($storageAdapter, 'getTemporaryUrl');

            if ($storage->getVisibility($file) === 'private' && $supportsTemporaryUrls) {
                return $storage->temporaryUrl(
                    $file,
                    now()->addMinutes(5)
                );
            }

            return $storage->url($file);
        });

        $this->saveUploadedFileUsing(function (BaseFileUpload $component, TemporaryUploadedFile $file): string {
            $storeMethod = $component->getVisibility() === 'public' ? 'storePubliclyAs' : 'storeAs';

            $filename = $component->shouldPreserveFilenames() ? $file->getClientOriginalName() : $file->getFilename();

            return $file->{$storeMethod}($component->getDirectory(), $filename, $component->getDiskName());
        });
    }

    /**
     * @param mixed[]|\Closure|\Illuminate\Contracts\Support\Arrayable $types
     * @return $this
     */
    public function acceptedFileTypes($types)
    {
        $this->acceptedFileTypes = $types;

        $this->rule(function () {
            $types = implode(',', ($this->getAcceptedFileTypes() ?? []));

            return "mimetypes:{$types}";
        });

        return $this;
    }

    /**
     * @param \Closure|string|null $directory
     * @return $this
     */
    public function directory($directory)
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * @return $this
     */
    public function disk($name)
    {
        $this->diskName = $name;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function enableReordering($condition = true)
    {
        $this->canReorder = $condition;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function disablePreview($condition = false)
    {
        $this->canPreview = $condition;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function preserveFilenames($condition = true)
    {
        $this->shouldPreserveFilenames = $condition;

        return $this;
    }

    /**
     * @param \Closure|int|null $size
     * @return $this
     */
    public function maxSize($size)
    {
        $this->maxSize = $size;

        $this->rule(function (): string {
            $size = $this->getMaxSize();

            return "max:{$size}";
        });

        return $this;
    }

    /**
     * @param \Closure|int|null $size
     * @return $this
     */
    public function minSize($size)
    {
        $this->minSize = $size;

        $this->rule(function (): string {
            $size = $this->getMinSize();

            return "min:{$size}";
        });

        return $this;
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
     * @param bool|\Closure $condition
     * @return $this
     */
    public function multiple($condition = true)
    {
        $this->isMultiple = $condition;

        return $this;
    }

    /**
     * @param \Closure|string|null $visibility
     * @return $this
     */
    public function visibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * @return $this
     */
    public function deleteUploadedFileUsing(?Closure $callback)
    {
        $this->deleteUploadedFileUsing = $callback;

        return $this;
    }

    /**
     * @return $this
     */
    public function getUploadedFileUrlUsing(?Closure $callback)
    {
        $this->getUploadedFileUrlUsing = $callback;

        return $this;
    }

    /**
     * @return $this
     */
    public function reorderUploadedFilesUsing(?Closure $callback)
    {
        $this->reorderUploadedFilesUsing = $callback;

        return $this;
    }

    /**
     * @return $this
     */
    public function saveUploadedFileUsing(?Closure $callback)
    {
        $this->saveUploadedFileUsing = $callback;

        return $this;
    }

    public function canReorder(): bool
    {
        return $this->evaluate($this->canReorder);
    }

    public function canPreview(): bool
    {
        return $this->evaluate($this->canPreview);
    }

    public function getAcceptedFileTypes(): ?array
    {
        $types = $this->evaluate($this->acceptedFileTypes);

        if ($types instanceof Arrayable) {
            $types = $types->toArray();
        }

        return $types;
    }

    public function getDirectory(): ?string
    {
        return $this->evaluate($this->directory);
    }

    public function getDisk(): Filesystem
    {
        return Storage::disk($this->getDiskName());
    }

    public function getDiskName(): string
    {
        return $this->evaluate($this->diskName) ?? config('forms.default_filesystem_disk');
    }

    public function getMaxSize(): ?int
    {
        return $this->evaluate($this->maxSize);
    }

    public function getMinSize(): ?int
    {
        return $this->evaluate($this->minSize);
    }

    public function getVisibility(): string
    {
        return $this->evaluate($this->visibility);
    }

    public function shouldPreserveFilenames(): bool
    {
        return $this->evaluate($this->shouldPreserveFilenames);
    }

    public function getValidationRules(): array
    {
        $rules = [
            $this->getRequiredValidationRule(),
            'array',
        ];

        if (filled($count = $this->maxFiles)) {
            $rules[] = "max:{$count}";
        }

        if (filled($count = $this->minFiles)) {
            $rules[] = "min:{$count}";
        }

        $rules[] = function (string $attribute, array $value, Closure $fail): void {
            $files = array_filter($value, function ($file) : bool {
                return $file instanceof TemporaryUploadedFile;
            });

            $name = $this->getName();

            $validator = Validator::make(
                [$name => $files],
                ["{$name}.*" => array_merge(['file'], parent::getValidationRules())],
                ["{$name}.*" => $this->getValidationAttribute()]
            );

            if (! $validator->fails()) {
                return;
            }

            $fail($validator->errors()->first());
        };

        return $rules;
    }

    /**
     * @return $this
     */
    public function deleteUploadedFile(string $fileKey)
    {
        $file = $this->removeUploadedFile($fileKey);

        if (blank($file)) {
            return $this;
        }

        $callback = $this->deleteUploadedFileUsing;

        if (! $callback) {
            return $this;
        }

        $this->evaluate($callback, [
            'file' => $file,
        ]);

        return $this;
    }

    /**
     * @return \Livewire\TemporaryUploadedFile|string|null
     */
    public function removeUploadedFile(string $fileKey)
    {
        $files = $this->getState();
        $file = $files[$fileKey] ?? null;

        if (! $file) {
            return null;
        }

        if ($file instanceof TemporaryUploadedFile) {
            $file->delete();
        }

        unset($files[$fileKey]);

        $this->state($files);

        return $file;
    }

    public function reorderUploadedFiles(array $fileKeys): void
    {
        if (! $this->canReorder) {
            return;
        }

        $fileKeys = array_flip($fileKeys);

        $state = collect($this->getState())
            ->sortBy(function ($file, $fileKey) use ($fileKeys) {
                return $fileKeys[$fileKey] ?? null;
            }) // $fileKey may not be present in $fileKeys if it was added to the state during the reorder call
            ->toArray();

        $this->state($state);
    }

    public function getUploadedFileUrl(string $fileKey): ?string
    {
        $files = $this->getState();

        $file = $files[$fileKey] ?? null;

        if (! $file) {
            return null;
        }

        $callback = $this->getUploadedFileUrlUsing;

        if (! $callback) {
            return null;
        }

        return $this->evaluate($callback, [
            'file' => $file,
        ]);
    }

    public function saveUploadedFiles(): void
    {
        if (blank($this->getState())) {
            $this->state([]);

            return;
        }

        if (! is_array($this->getState())) {
            $this->state([$this->getState()]);
        }

        $state = array_map(function ($file) {
            if (! $file instanceof TemporaryUploadedFile) {
                return $file;
            }

            $callback = $this->saveUploadedFileUsing;

            if (! $callback) {
                $file->delete();

                return $file;
            }

            $storedFile = $this->evaluate($callback, [
                'file' => $file,
            ]);

            $file->delete();

            return $storedFile;
        }, $this->getState());

        if ($this->canReorder && ($callback = $this->reorderUploadedFilesUsing)) {
            $state = $this->evaluate($callback, [
                'state' => $state,
            ]);
        }

        $this->state($state);
    }

    public function isMultiple(): bool
    {
        return $this->evaluate($this->isMultiple);
    }
}
