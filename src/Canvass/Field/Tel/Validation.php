<?php

namespace Canvass\Field\Tel;

use Canvass\Contract\FieldData;

final class Validation extends \Canvass\Field\AbstractField\Input\Validation
{
    protected $attributes_validation_rules = [
        'required' => ['required' => false,],
        'placeholder' => [
            'required' => false, 'allow_null' => true, 'data_type' => 'string',
        ],
        'minlength' => ['required' => false, 'numeric' => true,],
        'maxlength' => ['required' => false, 'numeric' => true,],
    ];

    public function populateValidationRulesFromFieldData(
        FieldData $field,
        array &$rules
    ) {
        self::populateTextBasedFieldRules($field, $rules);
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
        
        return $return;
    }
}
