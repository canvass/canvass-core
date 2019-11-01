<?php

namespace Canvass\Action\NestedField;

use Canvass\Contract\Action\Action;
use Canvass\Forge;
use Canvass\Support\FieldTypes;

class SelectInputType implements Action
{
    public function __invoke(int $form_id, int $field_id, int $sort = null)
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
