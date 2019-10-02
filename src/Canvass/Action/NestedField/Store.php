<?php

namespace Canvass\Action\NestedField;

use Canvass\Action\FormField\CreateChildField;
use Canvass\Contract\Action;
use Canvass\Contract\FieldAction;
use Canvass\Contract\NestedFieldAction;
use Canvass\Forge;

final class Store implements Action, FieldAction, NestedFieldAction
{
    /** @var \Canvass\Contract\FormModel */
    private $form;
    /** @var \Canvass\Contract\FormFieldModel */
    private $parent;

    public function __invoke(
        int $form_id,
        int $field_id,
        int $sort,
        string $type,
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

        $create = new CreateChildField(
            $this->form,
            $field,
            $this->parent,
            Forge::validator(),
            null,
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

        return Forge::success(
            sprintf(
                '%s has been saved.',
                $field->getData('label') ?? $field->getData('identifier')
            ),
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
