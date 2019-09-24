<?php

namespace Canvass\Action\FormField;

final class MoveField extends AbstractFieldAction
{
    public const UP = -1;
    public const DOWN = 1;

    public function run(int $direction)
    {
        $old_sort = (int) $this->field->getAttribute('sort');

        $new_sort = $old_sort + $direction;

        $other_field = $this->form->findFieldWithSortOf($new_sort);

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
