<?php

namespace Canvass\Action\FormField;

use Canvass\Action\CommonField\DeleteField;
use Canvass\Contract\Action;
use Canvass\Contract\FieldAction;
use Canvass\Exception\DeleteFailedException;
use Canvass\Forge;

final class Destroy implements Action, FieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    public function __invoke(int $form_id, int $field_id)
    {
        $this->form = Forge::form()->find($form_id, Forge::getOwnerId());

        $field = $this->form->findField($field_id);

        $destroyer = new DeleteField($this->form, $field, Forge::getOwnerId());

        $destroyed = $destroyer->run();

        if (! $destroyed) {
            throw new DeleteFailedException(
                'Could not delete field for unknown reasons.'
            );
        }

        $fields = $field->retrieveChildren();

        $not_deleted = [];

        foreach ($fields as $child) {
            $deleted = $child->delete();

            if (! $deleted) {
                $not_deleted[] = $field;
            }
        }

        if (count($not_deleted)) {
            throw new DeleteFailedException(
                'Deleted field but could not delete all of its nested fields.'
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
