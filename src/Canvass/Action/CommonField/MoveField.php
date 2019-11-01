<?php

namespace Canvass\Action\CommonField;

use Canvass\Exception\InvalidSortException;
use Canvass\Forge;

final class MoveField extends AbstractFieldAction
{
    public const UP = -1;
    public const DOWN = 1;

    public function run(int $direction, $parent_id = 0)
    {
        $old_sort = (int) $this->field->getData('sort');

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

        $this->field->setData('sort', $new_sort);

        $saved = $this->field->save();

        if (! $saved) {
            return false;
        }

        if (null === $other_field) {
            return true;
        }

        $other_field->setData('sort', $old_sort);

        $moved = false;

        try {
            $moved = $other_field->save();
        } catch (InvalidSortException $e) {
            $message = 'Moving the field would result in an invalid sort.';
        } catch (\Throwable $e) {
            Forge::logThrowable($e);

            $message = 'Could not move field for unknown reasons.';
        }

        if (! $moved) {
            return Forge::error($message, $this);
        }

        return Forge::success(
            sprintf(
                'Field, %s, has been moved down.',
                $this->field->getData('label') ??
                    $this->field->getData('identifier')
            ),
            $this
        );
    }
}
