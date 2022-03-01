<?php

namespace Filament\Tables\Filters\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Builder;

trait InteractsWithTableQuery
{
    protected ?Closure $modifyQueryUsing = null;

    public function apply(Builder $query, array $data = []): Builder
    {
        if ($this->isHidden()) {
            return $query;
        }

        if (! $this->hasQueryModificationCallback()) {
            return $query;
        }

        if (! ($data['isActive'] ?? true)) {
            return $query;
        }

        $callback = $this->modifyQueryUsing;
        $callback($query, $data);

        return $query;
    }

    /**
     * @return $this
     */
    public function query(?Closure $callback)
    {
        $this->modifyQueryUsing = $callback;

        return $this;
    }

    protected function hasQueryModificationCallback(): bool
    {
        return $this->modifyQueryUsing instanceof Closure;
    }
}
