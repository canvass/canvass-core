<?php

namespace Canvass\Action\Validation\FormField;

final class ValidateGroupField extends AbstractValidateFieldAction
{
    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'name' => true,
            'label' => true,
            'identifier' => true,
            'type' => true,
            'classes' => false,
            'value' => false,
            'help_text' => false,
        ];
    }
}
