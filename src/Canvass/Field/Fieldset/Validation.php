<?php

namespace Canvass\Field\Fieldset;

use Canvass\Contract\FieldData;
use Canvass\Support\FieldTypes;

final class Validation extends \Canvass\Field\AbstractField\AbstractValidateFieldAction
{
    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'label' => true,
            'identifier' => false,
            'wrap_classes' => false,
        ];
    }

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
