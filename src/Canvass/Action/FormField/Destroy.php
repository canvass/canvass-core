<?php

namespace Canvass\Action\FormField;

use Canvass\Action\CommonField\DeleteField;
use Canvass\Contract\Action;
use Canvass\Contract\FieldAction;
use Canvass\Forge;

final class Destroy implements Action, FieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    public function __invoke(int $form_id, int $field_id)
    {
        $this->form = Forge::form()->find($form_id, Forge::getOwnerId());

        $field = $this->form->findField($field_id);

        $destroyer = new DeleteField($this->form, $field, null);

        try {
            $destroyed = $destroyer->run();
        } catch (\Throwable $e) {
            Forge::logThrowable($e);

            $destroyed = false;
        }

        if (! $destroyed) {
            return Forge::error(
                'Could not delete field for unknown reasons.',
                $this
            );
        }

        return Forge::success(
            sprintf(
                'Field, %s, has been deleted.',
                $field->getData('label') ??
                    $field->getData('identifier')
            ),
            $this
        );
    }

    public function getFormId()
    {
        return $this->form->getId();
    }
}
