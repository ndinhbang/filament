<?php

namespace Filament\Commands\Concerns;

use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Support\Str;
use Throwable;

trait CanGenerateResources
{
    protected function getResourceFormSchema(string $model): string
    {
        $table = $this->getModelTable($model);

        if (! $table) {
            return $this->indentString('//');
        }

        $components = [];

        foreach ($table->getColumns() as $column) {
            if ($column->getAutoincrement()) {
                continue;
            }

            if (Str::of($column->getName())->endsWith([
                '_at',
                '_token',
            ])) {
                continue;
            }

            $componentData = [];

            switch (get_class($column->getType())) {
                case Types\BooleanType::class:
                    $type = Forms\Components\Toggle::class;
                    break;
                case Types\DateType::class:
                    $type = Forms\Components\DatePicker::class;
                    break;
                case Types\DateTimeType::class:
                    $type = Forms\Components\DateTimePicker::class;
                    break;
                case Types\TextType::class:
                    $type = Forms\Components\Textarea::class;
                    break;
                default:
                    $type = Forms\Components\TextInput::class;
                    break;
            }

            $componentData['type'] = $type;

            if ($type === Forms\Components\TextInput::class) {
                if (Str::of($column->getName())->contains(['email'])) {
                    $componentData['email'] = [];
                }

                if (Str::of($column->getName())->contains(['password'])) {
                    $componentData['password'] = [];
                }

                if (Str::of($column->getName())->contains(['phone', 'tel'])) {
                    $componentData['tel'] = [];
                }
            }

            if ($column->getNotnull()) {
                $componentData['required'] = [];
            }

            if ($length = $column->getLength()) {
                $componentData['maxLength'] = [$length];
            }

            $components[$column->getName()] = $componentData;
        }

        $output = count($components) ? '' : '//';

        foreach ($components as $componentName => $componentData) {
            // Constructor
            $output .= (string) Str::of($componentData['type'])->after('Filament\\');
            $output .= '::make(\'';
            $output .= $componentName;
            $output .= '\')';

            unset($componentData['type']);

            // Configuration
            foreach ($componentData as $methodName => $parameters) {
                $output .= PHP_EOL;
                $output .= '    ->';
                $output .= $methodName;
                $output .= '(';
                $output .= implode('\', \'', $parameters);
                $output .= ')';
            }

            // Termination
            $output .= ',';
            end($components);

            if (! (key($components) === $componentName)) {
                $output .= PHP_EOL;
            }
        }

        return $this->indentString($output);
    }

    protected function getResourceTableColumns(string $model): string
    {
        $table = $this->getModelTable($model);

        if (! $table) {
            return $this->indentString('//');
        }

        $columns = [];

        foreach ($table->getColumns() as $column) {
            if ($column->getAutoincrement()) {
                continue;
            }

            if (Str::of($column->getName())->endsWith([
                '_token',
            ])) {
                continue;
            }

            if (Str::of($column->getName())->contains([
                'password',
            ])) {
                continue;
            }

            $columnData = [];

            switch (get_class($column->getType())) {
                case Types\BooleanType::class:
                    $type = Tables\Columns\BooleanColumn::class;
                    break;
                default:
                    $type = Tables\Columns\TextColumn::class;
                    break;
            }

            $columnData['type'] = $type;

            if ($type === Tables\Columns\TextColumn::class) {
                if (get_class($column->getType()) === Types\DateType::class) {
                    $columnData['date'] = [];
                }

                if (get_class($column->getType()) === Types\DateTimeType::class) {
                    $columnData['dateTime'] = [];
                }
            }

            $columns[$column->getName()] = $columnData;
        }

        $output = count($columns) ? '' : '//';

        foreach ($columns as $columnName => $columnData) {
            // Constructor
            $output .= (string) Str::of($columnData['type'])->after('Filament\\');
            $output .= '::make(\'';
            $output .= $columnName;
            $output .= '\')';

            unset($columnData['type']);

            // Configuration
            foreach ($columnData as $methodName => $parameters) {
                $output .= PHP_EOL;
                $output .= '    ->';
                $output .= $methodName;
                $output .= '(';
                $output .= implode('\', \'', $parameters);
                $output .= ')';
            }

            // Termination
            $output .= ',';
            end($columns);

            if (! (key($columns) === $columnName)) {
                $output .= PHP_EOL;
            }
        }

        return $this->indentString($output);
    }

    protected function getModelTable(string $model): ?Table
    {
        if (! class_exists($model)) {
            return null;
        }

        $model = app($model);

        try {
            return $model
                ->getConnection()
                ->getDoctrineSchemaManager()
                ->listTableDetails($model->getTable());
        } catch (Throwable $exception) {
            return null;
        }
    }

    protected function indentString(string $string): string
    {
        return implode(
            PHP_EOL,
            array_map(
                function (string $line) {
                    return "                {$line}";
                },
                explode(PHP_EOL, $string)
            )
        );
    }
}
