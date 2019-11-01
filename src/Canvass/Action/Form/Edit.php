<?php

namespace Canvass\Action\Form;

use Canvass\Contract\Action\Action;
use Canvass\Contract\Action\FormAction;
use Canvass\Forge;

class Edit implements Action, FormAction
{
    private $form_id;

    public function __invoke(int $form_id)
    {
        $this->form_id = $form_id;

        return Forge::response()->respond(
            $this,
            ['form' => Forge::form()->find($form_id, Forge::getOwnerId())]
        );
    }

    public function getFormId()
    {
        return $this->form_id;
    }
}
