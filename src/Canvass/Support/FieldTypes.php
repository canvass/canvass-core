<?php

namespace Canvass\Support;

use Canvass\Action\Validation\FormField\AbstractValidateFieldAction;
use Canvass\Contract\Validate;
use Canvass\Contract\ValidationMap;
use Canvass\Exception\InvalidValidationData;
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
            'date' => 'Date',
            'email' => 'Email Address',
            'number' => 'Number',
//            'search' => 'Search Box',
            'tel' => 'Phone Number',
            'time' => 'Time',
            'url' => 'Url',
        ];
    }

    /**
     * @param string $type
     * @param string|null $alternate_type
     * @param \Canvass\Contract\Validate $validator
     * @param \Canvass\Contract\ValidationMap $validation_map
     * @return \Canvass\Action\Validation\FormField\AbstractValidateFieldAction
     * @throws \Canvass\Exception\InvalidValidationData
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
        $ucType = ucfirst(strtolower($type));

        $class = "\Canvass\Action\Validation\FormField\Validate{$ucType}Field";

        if (! class_exists($class)) {
            throw new InvalidValidationData(
                'There is no validation action for ' . $type
            );
        }

        return $class;
    }
}
