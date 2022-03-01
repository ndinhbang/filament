<?php

namespace Filament\Tables\Columns;

use Closure;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class ImageColumn extends Column
{
    protected string $view = 'tables::columns.image-column';

    /**
     * @var \Closure|string|null
     */
    protected $disk = null;

    /**
     * @var \Closure|int|string|null
     */
    protected $height = 40;

    /**
     * @var bool|\Closure
     */
    protected $isRounded = false;

    /**
     * @var \Closure|int|string|null
     */
    protected $width = null;

    protected function setUp(): void
    {
        $this->disk(config('tables.default_filesystem_disk'));
    }

    /**
     * @param \Closure|string|null $disk
     * @return $this
     */
    public function disk($disk)
    {
        $this->disk = $disk;

        return $this;
    }

    /**
     * @param \Closure|int|string|null $height
     * @return $this
     */
    public function height($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function rounded($condition = true)
    {
        $this->isRounded = $condition;

        return $this;
    }

    /**
     * @param \Closure|int|string $size
     * @return $this
     */
    public function size($size)
    {
        $this->width($size);
        $this->height($size);

        return $this;
    }

    /**
     * @param \Closure|int|string|null $width
     * @return $this
     */
    public function width($width)
    {
        $this->width = $width;

        return $this;
    }

    public function getDisk(): Filesystem
    {
        return Storage::disk($this->getDiskName());
    }

    public function getDiskName(): string
    {
        return $this->evaluate($this->disk) ?? config('tables.default_filesystem_disk');
    }

    public function getHeight(): ?string
    {
        $height = $this->evaluate($this->height);

        if ($height === null) {
            return null;
        }

        if (is_integer($height)) {
            return "{$height}px";
        }

        return $height;
    }

    public function getImagePath(): ?string
    {
        $state = $this->getState();

        if (! $state) {
            return null;
        }

        if (filter_var($state, FILTER_VALIDATE_URL) !== false) {
            return $state;
        }

        /** @var FilesystemAdapter $storage */
        $storage = $this->getDisk();

        // An ugly mess as we need to support both Flysystem v1 and v3.
        $storageAdapter = method_exists($storage, 'getAdapter') ? $storage->getAdapter() : (method_exists($storageDriver = $storage->getDriver(), 'getAdapter') ? $storageDriver->getAdapter() : null);
        $supportsTemporaryUrls = method_exists($storageAdapter, 'temporaryUrl') || method_exists($storageAdapter, 'getTemporaryUrl');

        if ($storage->getVisibility($state) === 'private' && $supportsTemporaryUrls) {
            return $storage->temporaryUrl(
                $state,
                now()->addMinutes(5)
            );
        }

        return $storage->url($state);
    }

    public function getWidth(): ?string
    {
        $width = $this->evaluate($this->width);

        if ($width === null) {
            return null;
        }

        if (is_integer($width)) {
            return "{$width}px";
        }

        return $width;
    }

    public function isRounded(): bool
    {
        return $this->evaluate($this->isRounded);
    }
}
