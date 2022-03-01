<?php

namespace Filament\Forms\Components\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Model;

trait BelongsToModel
{
    /**
     * @var \Closure|\Illuminate\Database\Eloquent\Model|string|null
     */
    protected $model = null;

    protected ?Closure $saveRelationshipsUsing = null;

    /**
     * @param \Closure|\Illuminate\Database\Eloquent\Model|string|null $model
     * @return $this
     */
    public function model($model = null)
    {
        $this->model = $model;

        return $this;
    }

    public function saveRelationships(): void
    {
        $callback = $this->saveRelationshipsUsing;

        if (! $callback) {
            return;
        }

        $this->evaluate($callback);
    }

    /**
     * @return $this
     */
    public function saveRelationshipsUsing(?Closure $callback)
    {
        $this->saveRelationshipsUsing = $callback;

        return $this;
    }

    public function getModel(): ?string
    {
        $model = $this->evaluate($this->model);

        if ($model instanceof Model) {
            return get_class($model);
        }

        if (filled($model)) {
            return $model;
        }

        return $this->getContainer()->getModel();
    }

    public function getRecord(): ?Model
    {
        $model = $this->evaluate($this->model);

        if ($model instanceof Model) {
            return $model;
        }

        if (is_string($model)) {
            return null;
        }

        return $this->getContainer()->getRecord();
    }

    public function getModelInstance(): ?Model
    {
        $model = $this->evaluate($this->model);

        if ($model === null) {
            return $this->getContainer()->getModelInstance();
        }

        if ($model instanceof Model) {
            return $model;
        }

        return app($model);
    }
}
