<?php

namespace Canvass\Action\Validation\FormField;

final class ValidateSelectField extends AbstractValidateFieldAction
{
    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'name' => true,
            'label' => true,
            'identifier' => true,
            'options' => true,
            'classes' => false,
            'help_text' => false,
        ];
    }
}
