<?php

namespace Canvass\Action\Validation\FormField;

final class ValidateNumberField extends AbstractValidateInputField
{
    protected $attributes_validation_rules = [
        'required' => ['required' => false,],
        'placeholder' => [
            'required' => false, 'allow_null' => true, 'data_type' => 'string',
        ],
        'step' => ['required' => false, 'numeric' => true,],
        'min' => ['required' => false, 'numeric' => true,],
        'max' => ['required' => false, 'numeric' => true,],
    ];

    public function convertAttributesData($attributes): array
    {
        $return = [];
        
        if (! empty($attributes['required'])) {
            $return['required'] = 'required';
        }
        
        if (! empty($attributes['placeholder'])) {
            $return['placeholder'] = $attributes['placeholder'];
        }
        
        if (! empty($attributes['min'])) {
            $return['min'] = (int) $attributes['min'];
        }
        
        if (! empty($attributes['max'])) {
            $return['max'] = (int) $attributes['max'];
        }

        if (! empty($attributes['step'])) {
            $return['step'] = (int) $attributes['step'];
        }
        
        return $return;
    }
}
