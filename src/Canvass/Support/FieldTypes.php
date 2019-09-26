<?php

namespace Canvass\Support;

class FieldTypes
{
    public static function get(bool $just_keys = false): array
    {
        $values = [
            'input' => 'Input',
            'textarea' => 'Textarea',
            'select' => 'Dropdown/Select',
//            'checkbox' => 'Checkbox',
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
}
