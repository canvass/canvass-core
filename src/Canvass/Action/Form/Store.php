<?php

namespace Canvass\Action\Form;

use Canvass\Action\Validation\ValidateFormData;
use Canvass\Contract\Action\Action;
use Canvass\Contract\Action\FormAction;
use Canvass\Forge;
use WebAnvil\Interfaces\ActionInterface;

class Store implements Action, FormAction, ActionInterface
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    private $request_keys = [
        'name', 'display_name', 'introduction', 'identifier', 'classes',
        'classes', 'button_text', 'button_classes', 'confirmation_message',
        'send_to', 'autoresponder_id'
    ];

    public function __invoke($data = null)
    {
        if (null === $data) {
            $data = Forge::requestData($this->request_keys);
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
