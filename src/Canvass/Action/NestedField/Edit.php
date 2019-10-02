<?php

namespace Canvass\Action\NestedField;

use Canvass\Contract\Action;
use Canvass\Forge;

final class Edit implements Action
{
    public function __invoke(int $form_id, int $parent_id, int $field_id)
    {
        $form = \Canvass\Forge::form()->find($form_id, Forge::getOwnerId());

        $parent = \Canvass\Forge::field()->find($parent_id);

        $field = \Canvass\Forge::field()->find($field_id);

        return Forge::response()->respond($this, [
            'form' => $form,
            'parent' => $parent,
            'field' => $field,
            'type' => $field->getData('canvass_type')
        ]);
    }
}
