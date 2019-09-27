<?php

namespace Canvass\Action\Validation\FormField;

final class ValidateTextField extends ValidateInputField
{
    protected $attributes_validation_rules = [
        'required' => ['required' => false,],
        'minlength' => ['required' => false, 'numeric' => true,],
        'maxlength' => ['required' => false, 'numeric' => true,],
        'placeholder' => ['required' => false, 'max_length' => 160,],
    ];

    public function hasValidatableAttributes(): bool
    {
        return true;
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
