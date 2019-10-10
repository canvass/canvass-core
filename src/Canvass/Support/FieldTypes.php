<?php

namespace Canvass\Support;

use Canvass\Contract\Validate;
use Canvass\Contract\ValidationMap;
use Canvass\Exception\InvalidValidationData;
use Canvass\Field\AbstractField\AbstractValidateFieldAction;
use Canvass\Forge;

final class FieldTypes
{
    public const INPUT_TYPES = [
        'checkbox', 'date', 'email', 'number', 'radio', 'search',
        'tel', 'text', 'time', 'url',
    ];

    private const GENERAL_TYPES_MAP = [
        'date' => 'input',
        'email' => 'input',
        'number' => 'input',
        'search' => 'input',
        'tel' => 'input',
        'text' => 'input',
        'time' => 'input',
        'url' => 'input',
        'textarea' => 'textarea',
        'select' => 'select',
        'option' => 'option',
        'checkbox' => 'checkbox',
        'radio' => 'radio',
        'checkbox-group' => 'group',
        'radio-group' => 'group',
        'fieldset' => 'fieldset',
        'divider' => 'divider',
        'columns' => 'columns',
        'column' => 'column',
    ];

    public static function get(bool $just_keys = false): array
    {
        $values = [
            'input' => 'Input',
            'textarea' => 'Textarea',
            'select' => 'Dropdown/Select',
            'checkbox' => 'Checkbox',
            'group' => 'Radio/Checkbox Group',
            'fieldset' => 'Fieldset',
            'divider' => 'Divider',
            'columns' => 'Columns',
            'column' => 'Column'
        ];

        if ($just_keys) {
            return array_keys($values);
        }

        return $values;
    }

    /**
     * @param string $type
     * @return bool
     * @throws \Canvass\Exception\InvalidValidationData
     */
    public static function isValid(string $type): bool
    {
        if (! in_array($type, self::get(true), true)) {
            return ! empty(self::getGeneralTypeFromType($type));
        }

        return true;
    }

    /**
     * @param string $type
     * @return string
     * @throws \Canvass\Exception\InvalidValidationData
     */
    public static function getGeneralTypeFromType(string $type): string
    {
        if (empty(self::GENERAL_TYPES_MAP[$type])) {
            throw new InvalidValidationData(
                "{$type} is not in accepted types list"
            );
        }

        return self::GENERAL_TYPES_MAP[$type];
    }

    public static function getInputTypes(): array
    {
        return [
            'text' => 'Generic Text',
            'tel' => 'Phone Number',
            'email' => 'Email Address',
            'number' => 'Number',
            'date' => 'Date',
            'time' => 'Time',
            'url' => 'Url',
        ];
    }

    /**
     * @param string $type
     * @param string|null $alternate_type
     * @param \Canvass\Contract\Validate|null $validator
     * @param \Canvass\Contract\ValidationMap|null $validation_map
     * @return \Canvass\Field\AbstractField\AbstractValidateFieldAction
     * @throws \Canvass\Exception\InvalidValidationData
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function getValidateAction(
        string $type,
        string $alternate_type = null,
        Validate $validator = null,
        ValidationMap $validation_map = null
    ): AbstractValidateFieldAction
    {
        try {
            $class = self::getValidateActionClassName($type);
        } catch (InvalidValidationData $e) {
            $class = self::getValidateActionClassName($alternate_type);
        }

        /** @var \Canvass\Action\Validation\AbstractValidateDataAction $validate */
        return new $class(
            $validator ?? Forge::validator(),
            $validation_map ?? Forge::validationMap()
        );
    }

    public static function getValidateActionClassName(string $type): string
    {
        $paths = array_reverse(Forge::getFieldPaths());
        
        $ucType = ucfirst(Str::camelCase($type));

        foreach ($paths as $path_set) {
            $class = "{$path_set['namespace']}\\{$ucType}\Validation";

            if (class_exists($class)) {
                return $class;
            }
        }

        throw new InvalidValidationData(
            'There is no validation action for ' . $type
        );
    }
}
