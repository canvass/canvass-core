<?php

namespace Canvass\Action\CommonField;

use Canvass\Contract\FormFieldModel;
use Canvass\Contract\FormModel;
use Canvass\Contract\Validate;
use Canvass\Contract\ValidationMap;
use Canvass\Support\FieldTypes;

final class StoreField extends AbstractFieldAction
{
    /** @var \Canvass\Contract\Validate */
    private $validator;
    /** @var \Canvass\Contract\ValidationMap */
    private $validation_map;
    /** @var \Canvass\Contract\Validate */
    private $field_validator;

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
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public function run($data, $type, $sort)
    {
        $general_type = FieldTypes::getGeneralTypeFromType($type);

        if (! empty($data['attributes'])) {
            $attributes = $data['attributes'];
        } else {
            $attributes = [];
        }
        unset($data['attributes']);

        $this->field_validator = FieldTypes::getValidateAction($type, $general_type);

        if (! $this->field_validator->validate($data)) {
            return false;
        }

        if ($this->field_validator->hasValidatableAttributes()) {
            $this->field_validator->validateAttributes($attributes);

            $this->field->setData(
                'attributes',
                $this->field_validator->convertAttributesData($attributes)
            );
        }

        foreach (array_keys(
            $this->field_validator->getDataColumnsMatchedWithRequiredBoolean()
        ) as $key) {
            if (isset($data[$key])) {
                $this->field->setData($key, $data[$key]);
            }
        }
        
        $this->field->setData('general_type', $general_type);

        $this->field->setData('form_id', $this->form->getId());

        $this->field->setData('sort', $sort + 1);

        $this->preSave($type);

        return $this->field->save();
    }

    private function preSave($type)
    {
        if ('divider' === $type) {
            $this->field->setData(
                'name',
                $this->field->getData('identifier')
            );
        }

        if (empty($this->field->getData('type'))) {
            $this->field->setData('type', $type);
        }
    }

    public function getFieldValidationErrorsString(): string
    {
        return $this->field_validator->getErrorsString();
    }

    public function getField(): FormFieldModel
    {
        return $this->field;
    }
}
