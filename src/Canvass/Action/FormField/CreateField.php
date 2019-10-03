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
        $general_type = FieldTypes::getGeneralTypeFromType($type);

        $attributes = $data['attributes'] ?? [];
        unset($data['attributes']);

        $validate = FieldTypes::getValidateAction($type, $general_type);

        if (! $validate->validate($data)) {
            return false;
        }

        if ($validate->hasValidatableAttributes()) {
            $validate->validateAttributes($attributes);

            $this->field->setData(
                'attributes',
                $validate->convertAttributesData($attributes)
            );
        }

        foreach (array_keys($validate::getValidationKeysWithRequiredValue()) as $key) {
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

    private function preSave(string $type): void
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
}
