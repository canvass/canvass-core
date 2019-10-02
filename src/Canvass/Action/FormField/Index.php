<?php

namespace Canvass\Action\FormField;

use Canvass\Contract\Action;
use Canvass\Contract\FieldAction;
use Canvass\Forge;

final class Index implements Action, FieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    public function __invoke(int $form_id)
    {
        $this->form = Forge::form()->find($form_id, Forge::getOwnerId());

        return Forge::response()->respond(
            $this,
            [
                'form' => $this->form,
                'fields' => Forge::field()
                    ->findAllByFormId($this->form->getId(), 0)
            ]
        );
    }

    public function getFormId()
    {
        return $this->form->getId();
    }
}
