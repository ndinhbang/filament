<?php

namespace Filament\Tables\Columns\Concerns;

use Closure;

trait CanCountRelatedModels
{
    /**
     * @var \Closure|string|null
     */
    protected $relationshipToCount = null;

    /**
     * @param \Closure|string|null $relationship
     * @return $this
     */
    public function counts($relationship)
    {
        $this->relationshipToCount = $relationship;

        return $this;
    }

    public function getRelationshipToCount(): ?string
    {
        return $this->evaluate($this->relationshipToCount);
    }
}
