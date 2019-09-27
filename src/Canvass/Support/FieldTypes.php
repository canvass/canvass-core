<?php

namespace Canvass\Support;

use Canvass\Exception\InvalidValidationData;

final class FieldTypes
{
    private const CANVASS_TYPES_MAP = [
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
            return ! empty(self::getCanvassTypeFromType($type));
        }

        return true;
    }

    /**
     * @param string $type
     * @return string
     * @throws \Canvass\Exception\InvalidValidationData
     */
    public static function getCanvassTypeFromType(string $type): string
    {
        if (empty(self::CANVASS_TYPES_MAP[$type])) {
            throw new InvalidValidationData(
                "{$type} is not in accepted types list"
            );
        }

        return self::CANVASS_TYPES_MAP[$type];
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
}
