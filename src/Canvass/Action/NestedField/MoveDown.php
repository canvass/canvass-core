<?php

namespace Canvass\Action\NestedField;

use Canvass\Action\CommonField\MoveField;
use Canvass\Contract\Action\Action;
use Canvass\Exception\InvalidSortException;
use Canvass\Forge;

final class MoveDown implements Action
{
    /**
     * @param int $form_id
     * @param int $field_id
     * @param int $option_id
     * @return mixed
     */
    public function __invoke($form_id, $field_id, $option_id)
    {
        $form = \Canvass\Forge::form()->find($form_id, Forge::getOwnerId());

        $parent = \Canvass\Forge::field()->find($field_id);

        $field = \Canvass\Forge::field()->find($option_id);

        $move = new MoveField($form, $field, Forge::getOwnerId());

        try {
            $move->run(MoveField::DOWN, $parent->id);
        } catch (InvalidSortException $e) {
            $message = 'Moving the field would result in an invalid sort.';
        } catch (\Throwable $e) {
           Forge::logThrowable($e);

            $message = 'Could not move field for unknown reasons.';
        }

        if (! empty($message)) {
            return Forge::error($message, $this);
        }

        $title = $field->getData('label');
        if (empty($title)) {
            $title = $field->getData('identifier');
        }

        return Forge::success(
            sprintf('Field, %s, has been moved down.', $title),
            $this
        );
    }
}
