<?php

namespace Filament\Forms\Components;

use Closure;

class FileUpload extends BaseFileUpload
{
    use Concerns\HasExtraAlpineAttributes;
    use Concerns\HasExtraInputAttributes;
    use Concerns\HasPlaceholder;

    protected string $view = 'forms::components.file-upload';

    /**
     * @var \Closure|string|null
     */
    protected $imageCropAspectRatio = null;

    /**
     * @var \Closure|string|null
     */
    protected $imagePreviewHeight = null;

    /**
     * @var \Closure|string|null
     */
    protected $imageResizeTargetHeight = null;

    /**
     * @var \Closure|string|null
     */
    protected $imageResizeTargetWidth = null;

    /**
     * @var bool|\Closure
     */
    protected $isAvatar = false;

    /**
     * @var \Closure|string
     */
    protected $loadingIndicatorPosition = 'right';

    /**
     * @var \Closure|string|null
     */
    protected $panelAspectRatio = null;

    /**
     * @var \Closure|string|null
     */
    protected $panelLayout = 'compact';

    /**
     * @var \Closure|string
     */
    protected $removeUploadedFileButtonPosition = 'left';

    /**
     * @var bool|\Closure
     */
    protected $shouldAppendFiles = false;

    /**
     * @var \Closure|string
     */
    protected $uploadButtonPosition = 'right';

    /**
     * @var \Closure|string
     */
    protected $uploadProgressIndicatorPosition = 'right';

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function appendFiles($condition = true)
    {
        $this->shouldAppendFiles = $condition;

        return $this;
    }

    /**
     * @return $this
     */
    public function avatar()
    {
        $this->isAvatar = true;

        $this->image();
        $this->imageCropAspectRatio('1:1');
        $this->imageResizeTargetHeight('500');
        $this->imageResizeTargetWidth('500');
        $this->loadingIndicatorPosition('center bottom');
        $this->panelLayout('compact circle');
        $this->removeUploadedFileButtonPosition('center bottom');
        $this->uploadButtonPosition('center bottom');
        $this->uploadProgressIndicatorPosition('center bottom');

        return $this;
    }

    /**
     * @param \Closure|string|null $label
     * @return $this
     */
    public function idleLabel($label)
    {
        $this->placeholder($label);

        return $this;
    }

    /**
     * @return $this
     */
    public function image()
    {
        $this->acceptedFileTypes([
            'image/*',
        ]);

        return $this;
    }

    /**
     * @param \Closure|string|null $ratio
     * @return $this
     */
    public function imageCropAspectRatio($ratio)
    {
        $this->imageCropAspectRatio = $ratio;

        return $this;
    }

    /**
     * @param \Closure|string|null $height
     * @return $this
     */
    public function imagePreviewHeight($height)
    {
        $this->imagePreviewHeight = $height;

        return $this;
    }

    /**
     * @param \Closure|string|null $height
     * @return $this
     */
    public function imageResizeTargetHeight($height)
    {
        $this->imageResizeTargetHeight = $height;

        return $this;
    }

    /**
     * @param \Closure|string|null $width
     * @return $this
     */
    public function imageResizeTargetWidth($width)
    {
        $this->imageResizeTargetWidth = $width;

        return $this;
    }

    /**
     * @param \Closure|string|null $position
     * @return $this
     */
    public function loadingIndicatorPosition($position)
    {
        $this->loadingIndicatorPosition = $position;

        return $this;
    }

    /**
     * @param \Closure|string|null $ratio
     * @return $this
     */
    public function panelAspectRatio($ratio)
    {
        $this->panelAspectRatio = $ratio;

        return $this;
    }

    /**
     * @param \Closure|string|null $layout
     * @return $this
     */
    public function panelLayout($layout)
    {
        $this->panelLayout = $layout;

        return $this;
    }

    /**
     * @param \Closure|string|null $position
     * @return $this
     */
    public function removeUploadedFileButtonPosition($position)
    {
        $this->removeUploadedFileButtonPosition = $position;

        return $this;
    }

    /**
     * @param \Closure|string|null $position
     * @return $this
     */
    public function uploadButtonPosition($position)
    {
        $this->uploadButtonPosition = $position;

        return $this;
    }

    /**
     * @param \Closure|string|null $position
     * @return $this
     */
    public function uploadProgressIndicatorPosition($position)
    {
        $this->uploadProgressIndicatorPosition = $position;

        return $this;
    }

    public function getImageCropAspectRatio(): ?string
    {
        return $this->evaluate($this->imageCropAspectRatio);
    }

    public function getImagePreviewHeight(): ?string
    {
        return $this->evaluate($this->imagePreviewHeight);
    }

    public function getImageResizeTargetHeight(): ?string
    {
        return $this->evaluate($this->imageResizeTargetHeight);
    }

    public function getImageResizeTargetWidth(): ?string
    {
        return $this->evaluate($this->imageResizeTargetWidth);
    }

    public function getLoadingIndicatorPosition(): string
    {
        return $this->evaluate($this->loadingIndicatorPosition);
    }

    public function getPanelAspectRatio(): ?string
    {
        return $this->evaluate($this->panelAspectRatio);
    }

    public function getPanelLayout(): ?string
    {
        return $this->evaluate($this->panelLayout);
    }

    public function getRemoveUploadedFileButtonPosition(): string
    {
        return $this->evaluate($this->removeUploadedFileButtonPosition);
    }

    public function getUploadButtonPosition(): string
    {
        return $this->evaluate($this->uploadButtonPosition);
    }

    public function getUploadProgressIndicatorPosition(): string
    {
        return $this->evaluate($this->uploadProgressIndicatorPosition);
    }

    public function isAvatar(): bool
    {
        return (bool) $this->evaluate($this->isAvatar);
    }

    public function shouldAppendFiles(): bool
    {
        return $this->evaluate($this->shouldAppendFiles);
    }
}
