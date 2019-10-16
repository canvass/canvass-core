<?php

namespace Canvass\Action\FormField;

use Canvass\Action\CommonField\UpdateField;
use Canvass\Contract\Action;
use Canvass\Contract\FieldAction;
use Canvass\Forge;

final class Update implements Action, FieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    public function __invoke(int $form_id, int $field_id, $data = null)
    {
        $this->form = Forge::form()->find($form_id, Forge::getOwnerId());

        $field = $this->form->findField($field_id);

        $update = new UpdateField(
            $this->form,
            $field,
            Forge::validator(),
            Forge::getOwnerId(),
            Forge::validationMap()
        );

        if (null === $data) {
            $data = Forge::requestData();
        }

        try {
            $updated = $update->run($data);
        } catch (\Throwable $e) {
            Forge::logThrowable($e);

            $updated = false;
        }

        if (! $updated) {
            return Forge::error('Could not update field.', $this);
        }

        return Forge::success(
            sprintf(
                '%s has been updated.',
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
