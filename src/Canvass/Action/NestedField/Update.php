<?php

namespace Canvass\Action\NestedField;

use Canvass\Action\CommonField\UpdateField;
use Canvass\Contract\Action\Action;
use Canvass\Contract\Action\FieldAction;
use Canvass\Contract\Action\NestedFieldAction;
use Canvass\Forge;
use WebAnvil\Interfaces\ActionInterface;

final class Update implements Action, FieldAction, NestedFieldAction, ActionInterface
{
    /** @var \Canvass\Contract\FormModel */
    private $form;
    /** @var \Canvass\Contract\FormFieldModel */
    private $parent;

    /**
     * @param int $form_id
     * @param int $parent_id
     * @param int $field_id
     * @param null $data
     * @return mixed
     */
    public function __invoke($form_id, $parent_id, $field_id, $data = null)
    {
        if (null === $data) {
            $data = Forge::requestData();
        }

        $this->form = \Canvass\Forge::form()->find($form_id, Forge::getOwnerId());

        $this->parent = $this->form->findField($parent_id);

        $field = $this->form->findField($field_id);

        $update = new UpdateField(
            $this->form,
            $field,
            Forge::validator(),
            Forge::getOwnerId(),
            Forge::validationMap()
        );

        try {
            $updated = $update->run($data, $field->getData('type'));
        } catch (\Throwable $e) {
            Forge::logThrowable($e);

            $updated = false;
        }

        if (! $updated) {
            return Forge::error('Could not update field option.', $this);
        }

        $title = $field->getData('label');
        if (empty($title)) {
            $title = $field->getData('identifier');
        }

        return Forge::success(
            sprintf('%s has been updated.', $title),
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
