<?php

namespace Canvass\Action\FormField;

use Canvass\Contract\Action\Action;
use Canvass\Contract\Action\FieldAction;
use Canvass\Forge;
use Canvass\Support\FieldTypes;

final class SelectInputType implements Action, FieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    /**
     * @param int $form_id
     * @param int $sort
     * @return bool|mixed
     */
    public function __invoke($form_id, $sort = null)
    {
        $this->form = Forge::form()->find($form_id, Forge::getOwnerId());

        return Forge::response()->respond(
            $this,
            [
                'form' => $this->form,
                'sort' => $sort,
                'types' => FieldTypes::getInputTypes(),
            ]
        );
    }

    public function getFormId()
    {
        return $this->form->getId();
    }
}
