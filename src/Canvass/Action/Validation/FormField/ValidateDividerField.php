<?php

namespace Canvass\Action\Validation\FormField;

final class ValidateDividerField extends AbstractValidateFieldAction
{
    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'identifier' => true,
            'classes' => false,
            'help_text' => false,
        ];
    }
}
