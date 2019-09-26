<?php

namespace Canvass\Action\FormField;

use Canvass\Exception\InvalidSortException;

final class MoveField extends AbstractFieldAction
{
    public const UP = -1;
    public const DOWN = 1;

    public function run(int $direction, $parent_id = 0)
    {
        $old_sort = (int) $this->field->getAttribute('sort');

        $new_sort = $old_sort + $direction;

        if ($new_sort < 1) {
            throw new InvalidSortException(
                'A field cannot have a sort less than one.'
            );
        }

        $fields = $this->form->findFields($parent_id);

        if ($new_sort > count($fields->toArray())) {
            throw new InvalidSortException(
                'A field cannot have a sort more than the number of fields.'
            );
        }

        $other_field = $this->form->findFieldWithSortOf($new_sort, $parent_id);

        $this->field->setAttribute('sort', $new_sort);

        $saved = $this->field->save();

        if (! $saved) {
            return false;
        }

        if (null === $other_field) {
            return true;
        }

        $other_field->setAttribute('sort', $old_sort);

        return $other_field->save();
    }
}
