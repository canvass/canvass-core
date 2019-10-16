<?php

namespace Canvass\Action\CommonField;

use Canvass\Action\CommonField\AbstractFieldAction;

final class DeleteField extends AbstractFieldAction
{
    public function run()
    {
        $sort = $this->field->getData('sort');

        $parent_id = $this->field->getData('parent_id');

        $deleted = $this->field->delete();

        if (! $deleted) {
            return false;
        }

        $fields = $this->form->findFieldsWithSortGreaterThan($sort, $parent_id);

        if (null === $fields) {
            return true;
        }

        foreach ($fields as $field) {
            $field->setData('sort', $field->getData('sort') - 1);

            $field->save();
        }

        return true;
    }
}
