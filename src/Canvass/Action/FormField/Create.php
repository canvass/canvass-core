<?php

namespace Canvass\Action\FormField;

use Canvass\Contract\Action\Action;
use Canvass\Contract\Action\FieldAction;
use Canvass\Forge;

final class Create implements Action, FieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    /**
     * @param int $form_id
     * @param string $type
     * @param int|null $sort
     * @return mixed
     * @throws \Canvass\Exception\InvalidValidationData
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public function __invoke($form_id, $type, $sort = null)
    {
        \Canvass\Support\FieldTypes::isValid($type);

        $this->form = Forge::form()->find($form_id, Forge::getOwnerId());

        return Forge::response()->respond(
            $this,
            [
                'form' => $this->form,
                'field' => Forge::field(),
                'type' => $type,
                'sort' => $sort,
            ]
        );
    }

    public function getFormId()
    {
        return $this->form->getId();
    }
}
