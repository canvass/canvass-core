<?php

namespace Canvass\Field\Text;

final class Validation extends \Canvass\Field\AbstractField\Input\Validation
{
    protected $attributes_validation_rules = [
        'required' => ['required' => false,],
        'minlength' => ['required' => false, 'numeric' => true,],
        'maxlength' => ['required' => false, 'numeric' => true,],
        'placeholder' => ['required' => false, 'max_length' => 160,],
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
        
        if (! empty($attributes['minlength'])) {
            $return['minlength'] = (int) $attributes['minlength'];
        }
        
        if (! empty($attributes['maxlength'])) {
            $return['maxlength'] = (int) $attributes['maxlength'];
        }
        
        return $return;
    }
}
