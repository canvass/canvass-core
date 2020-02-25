<?php

namespace Canvass\Action\Form;

use Canvass\Contract\Action\Action;
use Canvass\Contract\Action\FormAction;
use Canvass\Forge;

class Edit implements Action, FormAction
{
    private $form_id;

    /**
     * @param \Canvass\Action\Form\int $form_id
     * @return mixed
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public function __invoke($form_id)
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
