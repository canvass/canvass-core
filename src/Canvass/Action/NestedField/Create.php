<?php

namespace Canvass\Action\NestedField;

use Canvass\Contract\Action;
use Canvass\Forge;

final class Create implements Action
{
    /**
     * @param int $form_id
     * @param int $field_id
     * @param int $sort
     * @param string $type
     * @return mixed
     * @throws \Canvass\Exception\InvalidValidationData
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public function __invoke(
        int $form_id,
        int $field_id,
        int $sort,
        string $type
    )
    {
        \Canvass\Support\FieldTypes::isValid($type);

        $form = Forge::form()->find($form_id, Forge::getOwnerId());

        $field = Forge::field()->find($field_id);

        return Forge::response()->respond($this, [
            'form' => $form,
            'parent' => $field,
            'field' => Forge::field(),
            'type' => $type,
            'sort' => $sort,
        ]);
    }
}
