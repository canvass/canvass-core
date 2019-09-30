<?php

namespace Canvass\Action\Validation\FormField;

final class ValidateGroupField extends AbstractValidateFieldAction
{
    protected $attributes_validation_rules = [
        'required' => ['required' => false,],
    ];

    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'name' => true,
            'label' => true,
            'identifier' => true,
            'type' => true,
        ];
    }

    public function convertAttributesData($attributes): array
    {
        $return = [];

        if (! empty($attributes['required'])) {
            $return['required'] = 'required';
        }

        return $return;
    }
}
