<?php

namespace Canvass\Action\Form;

use Canvass\Action\Validation\ValidateFormData;
use Canvass\Contract\Action;
use Canvass\Contract\FieldAction;
use Canvass\Forge;

class Update implements Action, FieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    public function __invoke(int $form_id, $data = null)
    {
        /** @var \Canvass\Contract\FormModel $form */
        $this->form = Forge::form()->find($form_id, Forge::getOwnerId());

        if (null === $data) {
            $data = Forge::requestData([
                'name', 'introduction', 'redirect_url',
                'identifier', 'classes', 'button_text', 'button_classes'
            ]);
        }

        $validator = new ValidateFormData(
            Forge::validator(),
            Forge::validationMap()
        );

        $validator->validate($data);

        try {
            foreach ($data as $key => $value) {
                $this->form->setData($key, $value);
            }

            $updated = $this->form->save();
        } catch (\Throwable $e) {
            Forge::logThrowable($e);

            $updated = false;
        }

        if (! $updated) {
            return Forge::error(
                'Could not update form for unknown reasons.',
                $this
            );
        }

        return Forge::success(
            sprintf('%s has been updated.', $this->form->getData('name')),
            $this
        );
    }

    public function getFormId()
    {
        return $this->form->getId();
    }
}
