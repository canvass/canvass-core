<?php

namespace Canvass\Action\Form;

use Canvass\Action\Validation\ValidateFormData;
use Canvass\Contract\Action;
use Canvass\Contract\FieldAction;
use Canvass\Forge;

class Store implements Action, FieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    public function __invoke($data = null)
    {
        if (null === $data) {
            $data = Forge::requestData([
                'name', 'display_name', 'introduction', 'redirect_url',
                'identifier', 'classes', 'button_text', 'button_classes'
            ]);
        }

        $validator = new ValidateFormData(
            Forge::validator(),
            Forge::validationMap()
        );

        $validator->validate($data);

        $this->form = Forge::form();

        $this->form->setData('owner_id', Forge::getOwnerId());

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
            return Forge::error('Could not save form.', $this);
        }

        return Forge::success(
            sprintf('%s has been saved.', $this->form->getData('name')),
            $this
        );
    }

    public function getFormId()
    {
        return $this->form->getId();
    }
}
