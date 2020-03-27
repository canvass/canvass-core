<?php

namespace Canvass\Action\FormField;

use Canvass\Action\CommonField\StoreField;
use Canvass\Contract\Action\Action;
use Canvass\Contract\Action\FieldAction;
use Canvass\Forge;
use WebAnvil\Interfaces\ActionInterface;

final class Store implements Action, FieldAction, ActionInterface
{
    /** @var \Canvass\Contract\FormModel */
    private $form;

    /**
     * @param int $form_id
     * @param string $type
     * @param int|null $sort
     * @param null $data
     * @return mixed
     */
    public function __invoke(
        $form_id,
        $type,
        $sort = null,
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

        $title = $field->getData('label');
        if (empty($title)) {
            $title = $field->getData('identifier');
        }

        return Forge::success(
            sprintf('%s has been saved.', $title),
            $this
        );
    }

    public function getFormId()
    {
        return $this->form->getId();
    }
}
