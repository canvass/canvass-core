<?php

namespace Canvass\Field\Columns;

use Canvass\Contract\FieldData;
use Canvass\Support\FieldTypes;
use Canvass\Field\AbstractField\AbstractValidateFieldAction;

final class Validation extends AbstractValidateFieldAction
{
    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'identifier' => true,
            'wrap_classes' => false,
        ];
    }

    public function populateValidationRulesFromFieldData(
        FieldData $columns,
        array &$rules
    )
    {
        foreach ($columns['children'] as $field) {
            /** @var \Canvass\Action\Validation\FormField\ValidateColumnField $validator */
            $validator = FieldTypes::getValidateAction('column', 'column');

            $validator->populateValidationRulesFromFieldData($field, $rules);
        }
    }
}
