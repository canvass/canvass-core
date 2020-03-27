<?php

namespace Canvass\Action\NestedField;

use Canvass\Contract\Action\Action;
use Canvass\Forge;

final class Edit implements Action
{
    /**
     * @param int $form_id
     * @param int $parent_id
     * @param int $field_id
     * @return mixed
     */
    public function __invoke($form_id, $parent_id, $field_id)
    {
        $form = \Canvass\Forge::form()->find($form_id, Forge::getOwnerId());

        $parent = \Canvass\Forge::field()->find($parent_id);

        /** @var \Canvass\Contract\FormFieldModel $field */
        $field = \Canvass\Forge::field()->find($field_id);

        $children = $field->retrieveChildren();

        return Forge::response()->respond($this, [
            'form' => $form,
            'parent' => $parent,
            'field' => $field,
            'children' => $children,
            'type' => $field->getData('general_type')
        ]);
    }
}
