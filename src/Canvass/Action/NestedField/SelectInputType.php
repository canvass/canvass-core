<?php

namespace Canvass\Action\NestedField;

use Canvass\Contract\Action\Action;
use Canvass\Forge;
use Canvass\Support\FieldTypes;

class SelectInputType implements Action
{
    /**
     * @param int $form_id
     * @param int $field_id
     * @param int|null $sort
     * @return mixed
     */
    public function __invoke($form_id, $field_id, $sort = null)
    {
        $form = Forge::form()->find($form_id, Forge::getOwnerId());

        $field = Forge::field()->find($field_id);

        return Forge::response()->respond(
            $this,
            [
                'form' => $form,
                'field' => $field,
                'sort' => $sort,
                'types' => FieldTypes::getInputTypes(),
            ]
        );
    }
}
