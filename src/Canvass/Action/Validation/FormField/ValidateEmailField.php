<?php

namespace Canvass\Action\Validation\FormField;

use Canvass\Support\FieldData;
use Canvass\Support\Validation\Builder;

final class ValidateEmailField extends AbstractValidateInputField
{
    protected $attributes_validation_rules = [
        'required' => ['required' => false,],
        'placeholder' => [
            'required' => false, 'allow_null' => true, 'data_type' => 'string',
        ],
        'minlength' => ['required' => false, 'numeric' => true,],
        'maxlength' => ['required' => false, 'numeric' => true,],
        'multiple' => [
            'required' => false, 'allow_null' => true, 'data_type' => 'bool',
        ],
    ];

    public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    )
    {
        AbstractValidateInputField::populateTextBasedFieldRules(
            $field,
            $rules
        );
    }

    public function convertAttributesData($attributes): array
    {
        $return = [];
        
        if (! empty($attributes['required'])) {
            $return['required'] = 'required';
        }
        
        if (! empty($attributes['placeholder'])) {
            $return['placeholder'] = $attributes['placeholder'];
        }
        
        if (! empty($attributes['minlength'])) {
            $return['minlength'] = (int) $attributes['minlength'];
        }

        if (! empty($attributes['maxlength'])) {
            $return['maxlength'] = (int) $attributes['maxlength'];
        }

        if (! empty($attributes['multiple'])) {
            $return['multiple'] = true;
        }
        
        return $return;
    }
}
