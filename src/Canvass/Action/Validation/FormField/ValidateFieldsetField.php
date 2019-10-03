<?php

namespace Canvass\Action\Validation\FormField;

use Canvass\Support\FieldData;
use Canvass\Support\FieldTypes;

class ValidateFieldsetField extends AbstractValidateFieldAction
{
    public function populateValidationRulesFromFieldData(
        FieldData $fieldset,
        array &$rules
    ) {
        foreach ($fieldset['children'] as $field) {
            /** @var \Canvass\Action\Validation\FormField\AbstractValidateFieldAction $validator */
            $validator = FieldTypes::getValidateAction(
                $field['type'],
                $field['general_type']
            );

            $validator->populateValidationRulesFromFieldData($field, $rules);
        }
    }
}
