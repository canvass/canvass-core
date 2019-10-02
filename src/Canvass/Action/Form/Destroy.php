<?php

namespace Canvass\Action\Form;

use Canvass\Contract\Action;
use Canvass\Contract\FieldAction;
use Canvass\Forge;

class Destroy implements Action, FieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    public function __invoke(int $form_id)
    {
        $this->form = Forge::form()->find($form_id, Forge::getOwnerId());

        try {
            $destroyed = $this->form->delete();
        } catch (\Throwable $e) {
            Forge::logThrowable($e);

            $destroyed = false;
        }

        if (! $destroyed) {
            return Forge::error(
                'Could not delete form for unknown reasons.',
                $this
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
