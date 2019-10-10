<?php

namespace Canvass\Field\AbstractField\Input;

use Canvass\Field\AbstractField\AbstractValidateFieldAction;
use Canvass\Contract\FieldData;
use Canvass\Support\Validation\Builder;

abstract class Validation extends AbstractValidateFieldAction
{
    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'name' => true,
            'label' => true,
            'identifier' => true,
        ];
    }

    public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    )
    {
        self::populateTextBasedFieldRules($field, $rules);
    }

    public static function populateTextBasedFieldRules(
        FieldData $field,
        array &$rules
    )
    {
        $builder = Builder::start()
            ->required($field->hasAttribute('required'))
            ->isStringType();

        if ($field->hasAttribute('minlength')) {
            $builder->minLength($field->getAttribute('minlength'));
        }

        if ($field->hasAttribute('maxlength')) {
            $builder->maxLength($field->getAttribute('maxlength'));
        }

        $rules[$field['name']] = [
            'field' => $field,
            'rules' => $builder->build()
        ];
    }
}
