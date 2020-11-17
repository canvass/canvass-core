<?php

namespace Canvass\Action\Validation;

final class ValidateFormData extends AbstractValidateDataAction
{
    public function validate($data): bool
    {
        return parent::validate($data);
    }

    public function getValidationRules(): array
    {
        return [
            'name' => ['rules' => [
                'required' => true,
                'max_length' => 160,
            ]],
            'introduction' => ['rules' => [
                'required' => false,
            ]],
            'redirect_url' => ['rules' => [
                'required' => false,
                'max_length' => 160,
            ]],
            'confirmation_message' => ['rules' => [
                'required' => false,
            ]],
            'identifier' => ['rules' => [
                'required' => true,
                'max_length' => 160,
            ]],
            'classes' => ['rules' => [
                'required' => false,
                'max_length' => 160,
            ]],
            'button_text' => ['rules' => [
                'required' => true,
                'max_length' => 160,
            ]],
            'button_classes' => ['rules' => [
                'required' => false,
                'max_length' => 160,
            ]],
        ];
    }
}
