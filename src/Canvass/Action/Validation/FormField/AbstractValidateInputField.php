<?php

namespace Canvass\Action\Validation\FormField;

use Canvass\Support\FieldData;
use Canvass\Support\Validation\Builder;

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

        $rules[$field['name']] = $builder->build();
    }
}
