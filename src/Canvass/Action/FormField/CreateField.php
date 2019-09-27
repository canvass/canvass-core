<?php

namespace Canvass\Action\FormField;

use Canvass\Action\Validation\FormField\AbstractValidateFieldAction;
use Canvass\Contract\FormFieldModel;
use Canvass\Contract\FormModel;
use Canvass\Contract\Validate;
use Canvass\Contract\ValidationMap;
use Canvass\Exception\InvalidValidationData;
use Canvass\Support\FieldTypes;

final class CreateField extends AbstractFieldAction
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
    )
    {
        parent::__construct($form, $field, $owner_id);

        $this->validator = $validator;

        $this->validation_map = $validation_map;
    }

    /**
     * @param $data
     * @param string $type
     * @param int $sort
     * @return bool
     * @throws \Canvass\Exception\InvalidValidationData
     */
    public function run($data, string $type, int $sort): bool
    {
        $canvass_type = FieldTypes::getCanvassTypeFromType($type);

        $validate = $this->getValidateAction($type, $canvass_type);

        if (! $validate->validate($data)) {
            return false;
        }

        foreach (array_keys($validate::getValidationKeysWithRequiredValue()) as $key) {
            if (isset($data[$key])) {
                $this->field->setAttribute($key, $data[$key]);
            }
        }
        
        $this->field->setAttribute('canvass_type', $canvass_type);

        $this->field->setAttribute('form_id', $this->form->getId());

        $this->field->setAttribute('sort', $sort + 1);

        $this->preSave($type);

        return $this->field->save();
    }

    private function preSave(string $type): void
    {
        if ('divider' === $type) {
            $this->field->setAttribute(
                'name',
                $this->field->getAttribute('identifier')
            );
        }

        if (empty($this->field->getAttribute('type'))) {
            $this->field->setAttribute('type', $type);
        }
    }

    /**
     * @param string $type
     * @param string|null $alternate_type
     * @return \Canvass\Action\Validation\FormField\AbstractValidateFieldAction
     * @throws \Canvass\Exception\InvalidValidationData
     */
    protected function getValidateAction(
        string $type,
        string $alternate_type = null
    ): AbstractValidateFieldAction
    {
        try {
            $class = $this->getValidateActionClassName($type);
        } catch (InvalidValidationData $e) {
            $class = $this->getValidateActionClassName($alternate_type);
        }

        /** @var \Canvass\Action\Validation\AbstractValidateDataAction $validate */
        return new $class($this->validator, $this->validation_map);
    }

    protected function getValidateActionClassName(string $type): string
    {
        $ucType = ucfirst(strtolower($type));

        $class = "\Canvass\Action\Validation\FormField\Validate{$ucType}Field";

        if (! class_exists($class)) {
            throw new InvalidValidationData(
                'There is no validation action for ' . $type
            );
        }

        return $class;
    }
}
