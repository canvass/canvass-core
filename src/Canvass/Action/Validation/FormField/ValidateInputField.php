<?php

namespace Canvass\Action\Validation\FormField;

abstract class ValidateInputField extends AbstractValidateFieldAction
{
    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'name' => true,
            'label' => true,
            'identifier' => true,
            'classes' => false,
            'value' => false,
            'help_text' => false,
        ];
    }
}
