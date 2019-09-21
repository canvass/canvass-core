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
}
