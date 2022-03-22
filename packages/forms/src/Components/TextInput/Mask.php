<?php

namespace Filament\Forms\Components\TextInput;

use Closure;
use Illuminate\Contracts\Support\Jsonable;

class Mask implements Jsonable
{
    /**
     * @var int|null
     */
    protected $decimalPlaces = 2;

    /**
     * @var string|null
     */
    protected $decimalSeparator = '.';

    /**
     * @var mixed[]
     */
    protected $enum = [];

    /**
     * @var int|null
     */
    protected $fromValue;

    /**
     * @var bool
     */
    protected $hasLazyPlaceholder = true;

    /**
     * @var bool
     */
    protected $isNumeric = false;

    /**
     * @var bool
     */
    protected $isRange = false;

    /**
     * @var bool
     */
    protected $isSigned = true;

    /**
     * @var string|null
     */
    protected $jsonOptions;

    /**
     * @var mixed[]
     */
    protected $mapToDecimalSeparator = [','];

    /**
     * @var int|null
     */
    protected $maxLength;

    /**
     * @var int|null
     */
    protected $maxValue;

    /**
     * @var int|null
     */
    protected $minValue;

    protected $pattern = null;

    /**
     * @var mixed[]
     */
    protected $patternBlocks = [];

    /**
     * @var mixed[]
     */
    protected $patternDefinitions = [];

    /**
     * @var string
     */
    protected $placeholderCharacter = '_';

    /**
     * @var bool
     */
    protected $shouldAutofix = false;

    /**
     * @var bool
     */
    protected $shouldNormalizeZeros = true;

    /**
     * @var bool
     */
    protected $shouldOverwrite = false;

    /**
     * @var bool
     */
    protected $shouldPadFractionalZeros = false;

    /**
     * @var string|null
     */
    protected $thousandsSeparator;

    /**
     * @var int|null
     */
    protected $toValue;

    final public function __construct()
    {
    }

    /**
     * @return $this
     */
    public function autofix(bool $condition = true)
    {
        $this->shouldAutofix = $condition;

        return $this;
    }

    /**
     * @return $this
     */
    public function decimalPlaces(?int $places)
    {
        $this->decimalPlaces = $places;

        return $this;
    }

    /**
     * @return $this
     */
    public function decimalSeparator(?string $separator = '.')
    {
        $this->decimalSeparator = $separator;

        return $this;
    }

    /**
     * @return $this
     */
    public function enum(array $values)
    {
        $this->enum = $values;

        return $this;
    }

    /**
     * @return $this
     */
    public function from(?int $value)
    {
        $this->fromValue = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function integer()
    {
        $this->decimalPlaces(0);

        return $this;
    }

    /**
     * @return $this
     */
    public function jsonOptions(?string $json = null)
    {
        $this->jsonOptions = $json;

        return $this;
    }

    /**
     * @return $this
     */
    public function lazyPlaceholder(bool $condition = true)
    {
        $this->hasLazyPlaceholder = $condition;

        return $this;
    }

    /**
     * @return $this
     */
    public function mapToDecimalSeparator(array $characters)
    {
        $this->mapToDecimalSeparator = $characters;

        return $this;
    }

    /**
     * @return $this
     */
    public function maxLength(?int $value)
    {
        $this->maxLength = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function maxValue(?int $value)
    {
        $this->maxValue = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function minValue(?int $value)
    {
        $this->minValue = $value;

        return $this;
    }

    /**
     * @return $this
     */
    public function money(string $prefix = '$', string $thousandsSeparator = ',', int $decimalPlaces = 2)
    {
        $this
            ->patternBlocks([
                'money' => function (Mask $mask) use ($thousandsSeparator, $decimalPlaces) {
                    return $mask
                        ->numeric()
                        ->thousandsSeparator($thousandsSeparator)
                        ->decimalPlaces($decimalPlaces)
                        ->padFractionalZeros()
                        ->normalizeZeros(false);
                },
            ])
            ->pattern("{$prefix}money")
            ->lazyPlaceholder(false);

        return $this;
    }

    /**
     * @return $this
     */
    public function numeric(bool $condition = true)
    {
        $this->isNumeric = $condition;

        return $this;
    }

    /**
     * @return $this
     */
    public function normalizeZeros(bool $condition = true)
    {
        $this->shouldNormalizeZeros = $condition;

        return $this;
    }

    /**
     * @return $this
     */
    public function overwrite(bool $condition = true)
    {
        $this->shouldOverwrite = $condition;

        return $this;
    }

    /**
     * @return $this
     */
    public function padFractionalZeros(bool $condition = true)
    {
        $this->shouldPadFractionalZeros = $condition;

        return $this;
    }

    /**
     * @return $this
     */
    public function pattern($pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }

    /**
     * @return $this
     */
    public function patternBlocks(array $blocks)
    {
        $this->patternBlocks = $blocks;

        return $this;
    }

    /**
     * @return $this
     */
    public function patternDefinitions(array $definitions)
    {
        $this->patternDefinitions = $definitions;

        return $this;
    }

    /**
     * @return $this
     */
    public function placeholderCharacter(string $character)
    {
        $this->placeholderCharacter = $character;

        return $this;
    }

    /**
     * @return $this
     */
    public function positive()
    {
        $this->signed(false);

        return $this;
    }

    /**
     * @return $this
     */
    public function range(bool $condition = true)
    {
        $this->isRange = $condition;

        return $this;
    }

    /**
     * @return $this
     */
    public function signed(bool $condition = true)
    {
        $this->isSigned = $condition;

        return $this;
    }

    /**
     * @return $this
     */
    public function thousandsSeparator(?string $separator = ',')
    {
        $this->thousandsSeparator = $separator;

        return $this;
    }

    /**
     * @return $this
     */
    public function to(?int $value)
    {
        $this->toValue = $value;

        return $this;
    }

    protected function getArrayableConfiguration(): array
    {
        $configuration = [];

        if ($this->pattern !== null) {
            $configuration['mask'] = $this->pattern;
        } elseif ($this->enum !== []) {
            $configuration['mask'] = '{{ IMask.MaskedEnum }}';
            $configuration['enum'] = $this->enum;
        } elseif ($this->isNumeric) {
            $configuration['mask'] = '{{ Number }}';
        } elseif ($this->isRange) {
            $configuration['mask'] = '{{ IMask.MaskedRange }}';
        }

        if ($this->shouldAutofix) {
            $configuration['autofix'] = true;
        }

        if ($this->patternBlocks !== []) {
            $configuration['blocks'] = array_map(
                function (Closure $configuration) : array {
                    return $configuration(app(static::class))->getArrayableConfiguration();
                },
                $this->patternBlocks
            );
        }

        if ($this->patternDefinitions !== []) {
            $configuration['definitions'] = $this->patternDefinitions;
        }

        if ($this->fromValue !== null) {
            $configuration['from'] = $this->toValue;
        }

        if (! $this->hasLazyPlaceholder) {
            $configuration['lazy'] = false;
        }

        if ($this->mapToDecimalSeparator !== ['.']) {
            $configuration['mapToRadix'] = $this->mapToDecimalSeparator;
        }

        if ($this->maxLength !== null) {
            $configuration['maxLength'] = $this->maxLength;
        }

        if ($this->maxValue !== null) {
            $configuration['max'] = $this->maxValue;
        }

        if ($this->minValue !== null) {
            $configuration['min'] = $this->minValue;
        }

        if (! $this->shouldNormalizeZeros) {
            $configuration['normalizeZeros'] = false;
        }

        if (! $this->shouldOverwrite) {
            $configuration['overwrite'] = false;
        }

        if ($this->shouldPadFractionalZeros) {
            $configuration['padFractionalZeros'] = true;
        }

        if ($this->toValue !== null) {
            $configuration['to'] = $this->toValue;
        }

        if ($this->placeholderCharacter !== '_') {
            $configuration['placeholderChar'] = $this->placeholderCharacter;
        }

        if ($this->decimalSeparator !== ',') {
            $configuration['radix'] = $this->decimalSeparator;
        }

        if ($this->decimalPlaces !== 2) {
            $configuration['scale'] = $this->decimalPlaces;
        }

        if ($this->isSigned) {
            $configuration['signed'] = true;
        }

        if ($this->thousandsSeparator !== null) {
            $configuration['thousandsSeparator'] = $this->thousandsSeparator;
        }

        return $configuration;
    }

    public function toJson($options = 0): string
    {
        if (filled($this->jsonOptions)) {
            return $this->jsonOptions;
        }

        $json = json_encode($this->getArrayableConfiguration(), JSON_UNESCAPED_SLASHES | $options);

        return str_replace(
            [
                '"{{ ',
                ' }}"',
            ],
            '',
            $json
        );
    }
}
