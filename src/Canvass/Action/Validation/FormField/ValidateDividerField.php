<?php

namespace Canvass\Action\Validation\FormField;

use Canvass\Support\FieldData;

final class ValidateDividerField extends AbstractValidateFieldAction
{
    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'identifier' => true,
        ];
    }

    public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    )
    {
        return null;
    }
}
