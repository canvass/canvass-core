<?php

namespace Canvass\Action\Validation\FormField;

final class ValidateTextareaField extends AbstractValidateFieldAction
{
    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'name' => true,
            'label' => true,
            'identifier' => true,
            'classes' => false,
            'help_text' => false,
        ];
    }
}
