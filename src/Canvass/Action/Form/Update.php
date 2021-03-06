<?php

namespace Canvass\Action\Form;

use Canvass\Action\Validation\ValidateFormData;
use Canvass\Contract\Action\Action;
use Canvass\Contract\Action\FormAction;
use Canvass\Forge;

class Update implements Action, FormAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    private $request_keys = [
        'name', 'display_name', 'introduction', 'identifier', 'classes',
        'classes', 'button_text', 'button_classes', 'confirmation_message',
        'send_to', 'autoresponder_id'
    ];

    /**
     * @param \Canvass\Action\Form\int $form_id
     * @param null $data
     * @return mixed
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public function __invoke($form_id, $data = null)
    {
        /** @var \Canvass\Contract\FormModel $form */
        $this->form = Forge::form()->find($form_id, Forge::getOwnerId());

        if (null === $data) {
            $data = Forge::requestData($this->request_keys);
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
            sprintf(
                '%s has been updated.',
                $this->form->getData('name')
            ),
            $this
        );
    }

    public function getFormId()
    {
        return $this->form->getId();
    }
}
