<?php

namespace Canvass\Action\Validation;

abstract class AbstractValidateDataAction
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
        return $this->validateDataWithRules($data, $this->getValidationRules());
    }

    public function getErrors(): array
    {
        return $this->validator->getErrors();
    }

    public function getErrorsString(): string
    {
        return $this->validator->getErrorsString();
    }

    abstract public function getValidationRules(): array;

    protected function validateDataWithRules($data, $rules)
    {
        if (null !== $this->validationConverter) {
            $rules = $this->validationConverter->convertRulesToFormat($rules);
        }

        return $this->validator->validate($data, $rules);
    }
}
