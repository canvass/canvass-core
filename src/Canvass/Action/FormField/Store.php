<?php

namespace Canvass\Action\FormField;

use Canvass\Action\CommonField\StoreField;
use Canvass\Contract\Action\Action;
use Canvass\Contract\Action\FieldAction;
use Canvass\Forge;

final class Store implements Action, FieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    public function __invoke(
        int $form_id,
        string $type,
        int $sort = null,
        $data = null
    )
    {
        \Canvass\Support\FieldTypes::isValid($type);

        $this->form = Forge::form()->find($form_id, Forge::getOwnerId());

        $field = Forge::field();

        $create = new StoreField(
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
            $created = $create->run($data, $type, $sort);
        } catch (\Throwable $e) {
            Forge::logThrowable($e);

            $created = false;
        }

        if (! $created) {
            return Forge::error('Could not save field.', $this);
        }

        return Forge::success(
            sprintf(
                '%s has been saved.',
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
