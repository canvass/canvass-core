<?php

namespace Canvass\Action\Validation\FormField;

abstract class AbstractValidateInputField extends AbstractValidateFieldAction
{
    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'name' => true,
            'label' => true,
            'identifier' => true,
        ];
    }
}
