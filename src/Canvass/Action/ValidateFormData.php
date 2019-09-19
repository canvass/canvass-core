<?php

namespace Canvass\Action;

final class ValidateFormData
{
    /** @var \Canvass\Contract\Validate */
    private $validator;
    /** @var \Canvass\Contract\ValidationMap|null */
    private $validationConverter;

    /**
     * ValidateFormData constructor.
     * @param \Canvass\Contract\Validate $validator
     * @param \Canvass\Contract\ValidationMap|null $validationConverter
     */
    public function __construct($validator, $validationConverter = null)
    {
        $this->validator = $validator;
        $this->validationConverter = $validationConverter;
    }

    public function validate($data): bool
    {
        $rules = $this->getValidationRules();

        if (null !== $this->validationConverter) {
            $rules = $this->validationConverter->convertRulesToFormat($rules);
        }

        return $this->validator->validate($data, $rules);
    }

    public function getValidationRules(): array
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
