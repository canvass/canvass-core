<?php

namespace Canvass\Field\Column;

use Canvass\Field\AbstractField\AbstractValidateFieldAction;
use Canvass\Contract\FieldData;
use Canvass\Support\FieldTypes;

final class Validation extends AbstractValidateFieldAction
{
    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'identifier' => true,
            'classes' => false,
            'wrap_classes' => false,
            'help_text' => false,
        ];
    }

    public function populateValidationRulesFromFieldData(
        FieldData $column,
        array &$rules
    )
    {
        foreach ($column['children'] as $field) {
            /** @var \Canvass\Action\Validation\FormField\AbstractValidateFieldAction $validator */
            $validator = FieldTypes::getValidateAction(
                $field['type'],
                $field['general_type']
            );

            $validator->populateValidationRulesFromFieldData($field, $rules);
        }
    }
}
