<?php

namespace Canvass\Action\Form;

use Canvass\Contract\Action;
use Canvass\Contract\FieldAction;
use Canvass\Exception\DeleteFailedException;
use Canvass\Forge;

class Destroy implements Action, FieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    public function __invoke(int $form_id)
    {
        $this->form = Forge::form()->find($form_id, Forge::getOwnerId());

        $destroyed = $this->form->delete();

        if (! $destroyed) {
            throw new DeleteFailedException(
                'Could not delete form for unknown reasons.'
            );
        }

        $fields = $this->form->findFields();

        $not_deleted = [];

        foreach ($fields as $field) {
            $deleted = $field->delete();

            if (! $deleted) {
                $not_deleted[] = $field;
            }
        }

        if (count($not_deleted)) {
            throw new DeleteFailedException(
                'Deleted form, but could not delete all of form\'s field.'
            );
        }

        return Forge::success(
            sprintf('%s has been deleted.', $this->form->getData('name')),
            $this
        );
    }

    public function getFormId()
    {
        return $this->form->getId();
    }
}
