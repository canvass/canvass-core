<?php

namespace Canvass\Action\NestedField;

use Canvass\Action\CommonField\StoreField;
use Canvass\Contract\Action\Action;
use Canvass\Contract\Action\FieldAction;
use Canvass\Contract\Action\NestedFieldAction;
use Canvass\Forge;
use WebAnvil\Interfaces\ActionInterface;

final class Store implements Action, FieldAction, NestedFieldAction, ActionInterface
{
    /** @var \Canvass\Contract\FormModel */
    private $form;
    /** @var \Canvass\Contract\FormFieldModel */
    private $parent;

    /**
     * @param int $form_id
     * @param int $field_id
     * @param int $sort
     * @param string $type
     * @param array|null $data
     * @return mixed
     * @throws \Canvass\Exception\InvalidValidationData
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public function __invoke(
        $form_id,
        $field_id,
        $sort,
        $type,
        $data = null
    )
    {
        if (null === $data) {
            $data = Forge::requestData();
        }

        \Canvass\Support\FieldTypes::isValid($type);

        $this->form = \Canvass\Forge::form()->find($form_id, Forge::getOwnerId());

        $this->parent = $this->form->findField($field_id);

        /** @var \Canvass\Contract\FormFieldModel $field */
        $field = \Canvass\Forge::field();

        $field->setData('name', $this->parent->getData('name'));

        $field->setData('parent_id', $this->parent->getId());

        $create = new StoreField(
            $this->form,
            $field,
            Forge::validator(),
            Forge::getOwnerId(),
            Forge::validationMap()
        );

        try {
            $created = $create->run($data, $type, $sort);
        } catch (\Throwable $e) {
            Forge::logThrowable($e);

            $created = false;
        }

        if (! $created) {
            return Forge::error('Could not save nested field.', $this);
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

    public function getParentFieldId()
    {
        return $this->parent->getId();
    }
}
