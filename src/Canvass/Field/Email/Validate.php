<?php

namespace Canvass\Field\Email;

use Canvass\Contract\FieldData;

final class Validate extends \Canvass\Field\AbstractField\Input\Validate
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
