<?php

namespace Canvass\Action\FormField;

final class DeleteField extends AbstractFieldAction
{
    public function run()
    {
        $sort = $this->field->getAttribute('sort');

        $parent_id = $this->field->getAttribute('parent_id');

        $deleted = $this->field->delete();

        if (! $deleted) {
            return false;
        }

        $fields = $this->form->findFieldsWithSortGreaterThan($sort, $parent_id);

        if (null === $fields) {
            return true;
        }

        foreach ($fields as $field) {
            $field->setAttribute('sort', $field->getAttribute('sort') - 1);

            $field->save();
        }

        return true;
    }
}
