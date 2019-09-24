<?php

namespace Canvass\Action\Validation\FormField;

abstract class ValidateToggleChildField extends AbstractValidateFieldAction
{
    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'label' => true,
            'identifier' => true,
            'classes' => false,
            'value' => true,
            'help_text' => false,
        ];
    }
}
