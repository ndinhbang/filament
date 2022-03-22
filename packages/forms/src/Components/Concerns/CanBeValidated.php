<?php

namespace Filament\Forms\Components\Concerns;

use Closure;
use Filament\Forms\Components\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

trait CanBeValidated
{
    /**
     * @var bool|\Closure
     */
    protected $isRequired = false;

    /**
     * @var mixed[]
     */
    protected $rules = [];

    /**
     * @var \Closure|string|null
     */
    protected $validationAttribute = null;

    /**
     * @param \Closure|string|null $table
     * @param \Closure|string|null $column
     * @return $this
     */
    public function exists($table = null, $column = null, ?Closure $callback = null)
    {
        $this->rule(function (Field $component, ?string $model) use ($callback, $column, $table) {
            $table = $component->evaluate($table) ?? $model;
            $column = $component->evaluate($column) ?? $component->getName();

            $rule = Rule::exists($table, $column);

            if ($callback) {
                $rule = $this->evaluate($callback, [
                    'rule' => $rule,
                ]);
            }

            return $rule;
        }, function (Field $component, ?string $model) use ($table) : bool {
            return (bool) ($component->evaluate($table) ?? $model);
        });

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function nullable($condition = true)
    {
        $this->required(function (Field $component) use ($condition): bool {
            return ! $component->evaluate($condition);
        });

        return $this;
    }

    /**
     * @param bool|\Closure $condition
     * @return $this
     */
    public function required($condition = true)
    {
        $this->isRequired = $condition;

        return $this;
    }

    /**
     * @param object|string $rule
     * @param bool|\Closure $condition
     * @return $this
     */
    public function rule($rule, $condition = true)
    {
        $this->rules = array_merge(
            $this->rules,
            [[$rule, $condition]]
        );

        return $this;
    }

    /**
     * @param mixed[]|string $rules
     * @param bool|\Closure $condition
     * @return $this
     */
    public function rules($rules, $condition = true)
    {
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        $this->rules = array_merge(
            $this->rules,
            array_map(function ($rule) use ($condition) {
                return [$rule, $condition];
            }, $rules)
        );

        return $this;
    }

    /**
     * @param \Closure|string $date
     * @return $this
     */
    public function after($date, bool $isStatePathAbsolute = false)
    {
        return $this->dateComparisonRule('after', $date, $isStatePathAbsolute);
    }

    /**
     * @param \Closure|string $date
     * @return $this
     */
    public function afterOrEqual($date, bool $isStatePathAbsolute = false)
    {
        return $this->dateComparisonRule('after_or_equal', $date, $isStatePathAbsolute);
    }

    /**
     * @param \Closure|string $date
     * @return $this
     */
    public function before($date, bool $isStatePathAbsolute = false)
    {
        return $this->dateComparisonRule('before', $date, $isStatePathAbsolute);
    }

    /**
     * @param \Closure|string $date
     * @return $this
     */
    public function beforeOrEqual($date, bool $isStatePathAbsolute = false)
    {
        return $this->dateComparisonRule('before_or_equal', $date, $isStatePathAbsolute);
    }

    /**
     * @param \Closure|string $statePath
     * @return $this
     */
    public function different($statePath, bool $isStatePathAbsolute = false)
    {
        return $this->fieldComparisonRule('different', $statePath, $isStatePathAbsolute);
    }

    /**
     * @param \Closure|string $statePath
     * @return $this
     */
    public function gt($statePath, bool $isStatePathAbsolute = false)
    {
        return $this->fieldComparisonRule('gt', $statePath, $isStatePathAbsolute);
    }

    /**
     * @param \Closure|string $statePath
     * @return $this
     */
    public function gte($statePath, bool $isStatePathAbsolute = false)
    {
        return $this->fieldComparisonRule('gte', $statePath, $isStatePathAbsolute);
    }

    /**
     * @param \Closure|string $statePath
     * @return $this
     */
    public function lt($statePath, bool $isStatePathAbsolute = false)
    {
        return $this->fieldComparisonRule('lt', $statePath, $isStatePathAbsolute);
    }

    /**
     * @param \Closure|string $statePath
     * @return $this
     */
    public function lte($statePath, bool $isStatePathAbsolute = false)
    {
        return $this->fieldComparisonRule('lte', $statePath, $isStatePathAbsolute);
    }

    /**
     * @param \Closure|string $statePath
     * @return $this
     */
    public function same($statePath, bool $isStatePathAbsolute = false)
    {
        return $this->fieldComparisonRule('same', $statePath, $isStatePathAbsolute);
    }

    /**
     * @param \Closure|string|null $table
     * @param \Closure|string|null $column
     * @param \Closure|\Illuminate\Database\Eloquent\Model $ignorable
     * @return $this
     */
    public function unique($table = null, $column = null, $ignorable = null, ?Closure $callback = null)
    {
        $this->rule(function (Field $component, ?string $model) use ($callback, $column, $ignorable, $table) {
            $table = $component->evaluate($table) ?? $model;
            $column = $component->evaluate($column) ?? $component->getName();
            $ignorable = $component->evaluate($ignorable);

            $rule = Rule::unique($table, $column)
                ->when(
                    $ignorable,
                    function (Unique $rule) use ($ignorable) {
                        return $rule->ignore(
                            $ignorable->getOriginal($ignorable->getKeyName()),
                            $ignorable->getKeyName()
                        );
                    }
                );

            if ($callback) {
                $rule = $this->evaluate($callback, [
                    'rule' => $rule,
                ]);
            }

            return $rule;
        }, function (Field $component, ?String $model) use ($table) : bool {
            return (bool) ($component->evaluate($table) ?? $model);
        });

        return $this;
    }

    /**
     * @param \Closure|string|null $label
     * @return $this
     */
    public function validationAttribute($label)
    {
        $this->validationAttribute = $label;

        return $this;
    }

    public function getRequiredValidationRule(): string
    {
        return $this->isRequired() ? 'required' : 'nullable';
    }

    public function getValidationAttribute(): string
    {
        return $this->evaluate($this->validationAttribute) ?? lcfirst($this->getLabel());
    }

    public function getValidationRules(): array
    {
        $rules = [
            $this->getRequiredValidationRule(),
        ];

        foreach ($this->rules as [$rule, $condition]) {
            if (is_numeric($rule)) {
                $rules[] = $this->evaluate($condition);
            } elseif ($this->evaluate($condition)) {
                $rules[] = $this->evaluate($rule);
            }
        }

        return $rules;
    }

    public function isRequired(): bool
    {
        return (bool) $this->evaluate($this->isRequired);
    }

    /**
     * @param \Closure|string $date
     * @return $this
     */
    protected function dateComparisonRule(string $rule, $date, bool $isStatePathAbsolute = false)
    {
        $this->rule(function (Field $component) use ($date, $isStatePathAbsolute, $rule): string {
            $date = $component->evaluate($date);

            if (! (strtotime($date) && $isStatePathAbsolute)) {
                $containerStatePath = $component->getContainer()->getStatePath();

                if ($containerStatePath) {
                    $date = "{$containerStatePath}.{$date}";
                }
            }

            return "{$rule}:{$date}";
        }, function (Field $component) use ($date) : bool {
            return (bool) $component->evaluate($date);
        });

        return $this;
    }

    /**
     * @param \Closure|string $statePath
     * @return $this
     */
    protected function fieldComparisonRule(string $rule, $statePath, bool $isStatePathAbsolute = false)
    {
        $this->rule(function (Field $component) use ($isStatePathAbsolute, $rule, $statePath): string {
            $statePath = $component->evaluate($statePath);

            if (! $isStatePathAbsolute) {
                $containerStatePath = $component->getContainer()->getStatePath();

                if ($containerStatePath) {
                    $statePath = "{$containerStatePath}.{$statePath}";
                }
            }

            return "{$rule}:{$statePath}";
        }, function (Field $component) use ($statePath) : bool {
            return (bool) $component->evaluate($statePath);
        });

        return $this;
    }
}
