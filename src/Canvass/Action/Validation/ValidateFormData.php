<?php

namespace Canvass\Action\Validation;

final class ValidateFormData extends AbstractValidateDataAction
{
    /** @return array */
    public function getValidationRules()
    {
        return [
            'name' => [
                'required' => true,
                'max_length' => 160,
            ],
            'introduction' => [
                'required' => false,
            ],
            'redirect_url' => [
                'required' => false,
                'max_length' => 160,
            ],
            'identifier' => [
                'required' => true,
                'max_length' => 160,
            ],
            'classes' => [
                'required' => false,
                'max_length' => 160,
            ],
            'button_text' => [
                'required' => true,
                'max_length' => 160,
            ],
            'button_classes' => [
                'required' => false,
                'max_length' => 160,
            ],
        ];
    }
}
