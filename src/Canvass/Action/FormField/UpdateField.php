<?php

namespace Canvass\Action\FormField;

use Canvass\Action\Validation\FormField\AbstractValidateFieldAction;
use Canvass\Contract\FormFieldModel;
use Canvass\Contract\FormModel;
use Canvass\Contract\Validate;
use Canvass\Contract\ValidationMap;

final class UpdateField extends AbstractFieldAction
{
    /** @var \Canvass\Contract\Validate */
    private $validator;
    /** @var \Canvass\Contract\ValidationMap */
    private $validation_map;

    public function __construct(
        FormModel $form,
        FormFieldModel $field,
        Validate $validator,
        $owner_id = null,
        ValidationMap $validation_map = null
    ) {
        parent::__construct($form, $field, $owner_id);

        $this->validator = $validator;

        $this->validation_map = $validation_map;
    }

    /**
     * @param $data
     * @param string|null $type The type to use for data validation
     * @param bool $clear_unset_data_keys If a key from the validation list is not in the $data array, should it be set to null or be left alone?
     * @return bool
     */
    public function run(
        $data,
        $type = null,
        bool $clear_unset_data_keys = false
    ): bool
    {
        $validate = $this->getValidateAction(
            $type ?? $this->field->getHtmlType()
        );

        if (! $validate->validate($data)) {
            return false;
        }

        foreach (array_keys($validate::getValidationKeysWithRequiredValue()) as $key) {
            if (isset($data[$key])) {
                $this->field->setAttribute($key, $data[$key]);
            } elseif ($clear_unset_data_keys) {
                $this->field->setAttribute($key, null);
            }
        }

        return $this->field->save();
    }

    protected function getValidateAction(string $type): AbstractValidateFieldAction
    {
        $ucType = ucfirst(strtolower($type));

        $class = "\Canvass\Action\Validation\FormField\Validate{$ucType}Field";

        /** @var \Canvass\Action\Validation\AbstractValidateDataAction $validate */
        return new $class($this->validator, $this->validation_map);
    }
}