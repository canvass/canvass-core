<?php

namespace Canvass\Action\FormField;

use Canvass\Action\Validation\FormField\AbstractValidateFieldAction;
use Canvass\Contract\FormFieldModel;
use Canvass\Contract\FormModel;
use Canvass\Contract\Validate;
use Canvass\Contract\ValidationMap;

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
    ) {
        parent::__construct($form, $field, $owner_id);

        $this->validator = $validator;

        $this->validation_map = $validation_map;
    }

    public function run($data, string $type, int $sort): bool
    {
        $validate = $this->getValidateAction($type);

        if (! $validate->validate($data)) {
            return false;
        }

        foreach (array_keys($validate::getValidationKeysWithRequiredValue()) as $key) {
            if (isset($data[$key])) {
                $this->field->setAttribute($key, $data[$key]);
            }
        }

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

    protected function getValidateAction(string $type): AbstractValidateFieldAction
    {
        $ucType = ucfirst(strtolower($type));

        $class = "\Canvass\Action\Validation\FormField\Validate{$ucType}Field";

        /** @var \Canvass\Action\Validation\AbstractValidateDataAction $validate */
        return new $class($this->validator, $this->validation_map);
    }
}
