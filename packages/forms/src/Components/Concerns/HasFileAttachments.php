<?php

namespace Filament\Forms\Components\Concerns;

use Closure;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Livewire\TemporaryUploadedFile;

trait HasFileAttachments
{
    /**
     * @var \Closure|string|null
     */
    protected $fileAttachmentsDirectory = null;

    /**
     * @var \Closure|string|null
     */
    protected $fileAttachmentsDiskName = null;

    protected ?Closure $getUploadedAttachmentUrlUsing = null;

    protected ?Closure $saveUploadedFileAttachmentsUsing = null;

    /**
     * @var \Closure|string
     */
    protected $fileAttachmentsVisibility = 'public';

    /**
     * @param \Closure|string|null $directory
     * @return $this
     */
    public function fileAttachmentsDirectory($directory)
    {
        $this->fileAttachmentsDirectory = $directory;

        return $this;
    }

    /**
     * @param \Closure|string|null $name
     * @return $this
     */
    public function fileAttachmentsDisk($name)
    {
        $this->fileAttachmentsDiskName = $name;

        return $this;
    }

    public function saveUploadedFileAttachment(TemporaryUploadedFile $attachment): ?string
    {
        if ($callback = $this->saveUploadedFileAttachmentsUsing) {
            $file = $this->evaluate($callback, [
                'file' => $attachment,
            ]);
        } else {
            $file = $this->handleFileAttachmentUpload($attachment);
        }

        if ($callback = $this->getUploadedAttachmentUrlUsing) {
            return $this->evaluate($callback, [
                'file' => $file,
            ]);
        }

        return $this->handleUploadedAttachmentUrlRetrieval($file);
    }

    /**
     * @param \Closure|string $visibility
     * @return $this
     */
    public function fileAttachmentsVisibility($visibility)
    {
        $this->fileAttachmentsVisibility = $visibility;

        return $this;
    }

    /**
     * @param \Closure|null $callback
     * @return $this
     */
    public function getUploadedAttachmentUrlUsing($callback)
    {
        $this->getUploadedAttachmentUrlUsing = $callback;

        return $this;
    }

    /**
     * @param \Closure|null $callback
     * @return $this
     */
    public function saveUploadedFileAttachmentsUsing($callback)
    {
        $this->saveUploadedFileAttachmentsUsing = $callback;

        return $this;
    }

    public function getFileAttachmentsDirectory(): ?string
    {
        return $this->evaluate($this->fileAttachmentsDirectory);
    }

    public function getFileAttachmentsDisk(): Filesystem
    {
        return Storage::disk($this->getFileAttachmentsDiskName());
    }

    public function getFileAttachmentsDiskName(): string
    {
        return $this->evaluate($this->fileAttachmentsDiskName) ?? config('forms.default_filesystem_disk');
    }

    public function getFileAttachmentsVisibility(): string
    {
        return $this->evaluate($this->fileAttachmentsVisibility);
    }

    protected function handleFileAttachmentUpload($file)
    {
        $storeMethod = $this->getFileAttachmentsVisibility() === 'public' ? 'storePublicly' : 'store';

        return $file->{$storeMethod}($this->getFileAttachmentsDirectory(), $this->getFileAttachmentsDiskName());
    }

    protected function handleUploadedAttachmentUrlRetrieval($file): ?string
    {
        /** @var FilesystemAdapter $storage */
        $storage = $this->getFileAttachmentsDisk();

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
    }
}
