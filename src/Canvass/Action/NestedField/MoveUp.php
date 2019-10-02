<?php

namespace Canvass\Action\NestedField;

use Canvass\Action\FormField\MoveField;
use Canvass\Contract\Action;
use Canvass\Exception\InvalidSortException;
use Canvass\Forge;

final class MoveUp implements Action
{
    public function __invoke(int $form_id, int $field_id, int $option_id)
    {
        $form = \Canvass\Forge::form()->find($form_id, Forge::getOwnerId());

        $parent = \Canvass\Forge::field()->find($field_id);

        $field = \Canvass\Forge::field()->find($option_id);

        $move = new MoveField($form, $field, Forge::getOwnerId());

        try {
            $move->run(MoveField::UP, $parent->id);
        } catch (InvalidSortException $e) {
            $message = 'Moving the field would result in an invalid sort.';
        } catch (\Throwable $e) {
            Forge::logThrowable($e);

            $message = 'Could not move field for unknown reasons.';
        }

        if (! empty($message)) {
            return Forge::error($message, $this);
        }

        return Forge::success(
            sprintf(
                'Field, %s, has been moved up.',
                $field->getData('label') ?? $field->getData('identifier')
            ),
            $this
        );


//            redirect()->route('form_field.edit', [$form->id, $parent->id])
//            ->with(
//                'success',
//            );
    }
}
