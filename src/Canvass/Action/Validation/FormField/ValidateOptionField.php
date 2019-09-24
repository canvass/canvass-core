<?php

namespace Canvass\Action\Validation\FormField;

final class ValidateOptionField extends AbstractValidateFieldAction
{
    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'label' => true,
            'value' => true,
        ];
    }
}
