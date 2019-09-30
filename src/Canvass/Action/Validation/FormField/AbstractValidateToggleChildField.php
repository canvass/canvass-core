<?php

namespace Canvass\Action\Validation\FormField;

abstract class AbstractValidateToggleChildField extends AbstractValidateFieldAction
{
    protected $attributes_validation_rules = [
        'checked' => ['checked' => false,],
    ];

    public static function getValidationKeysWithRequiredValue(): array
    {
        return [
            'label' => true,
            'identifier' => true,
            'value' => true,
        ];
    }

    public function convertAttributesData($attributes): array
    {
        $return = [];

        if (! empty($attributes['checked'])) {
            $return['checked'] = 'checked';
        }

        return $return;
    }
}
