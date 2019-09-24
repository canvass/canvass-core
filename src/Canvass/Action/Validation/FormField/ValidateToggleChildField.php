<?php

namespace Canvass\Action\Validation\FormField;

abstract class ValidateToggleChildField extends AbstractValidateFieldAction
{
    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'name' => false,
            'label' => true,
            'identifier' => true,
            'type' => false,
            'options' => false,
            'classes' => false,
            'value' => true,
            'help_text' => false,
        ];
    }
}
