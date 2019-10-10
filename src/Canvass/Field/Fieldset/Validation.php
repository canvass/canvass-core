<?php

namespace Canvass\Field\Fieldset;

use Canvass\Contract\FieldData;

final class Validation extends \Canvass\Field\AbstractField\Input\Validation
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
