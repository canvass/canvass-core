<?php

namespace Canvass\Action\NestedField;

use Canvass\Action\CommonField\DeleteField;
use Canvass\Contract\Action\Action;
use Canvass\Contract\Action\FieldAction;
use Canvass\Contract\Action\NestedFieldAction;
use Canvass\Exception\DeleteFailedException;
use Canvass\Forge;
use WebAnvil\Interfaces\ActionInterface;

final class Destroy implements Action, FieldAction, NestedFieldAction, ActionInterface
{
    /** @var \Canvass\Contract\FormModel */
    private $form;
    /** @var \Canvass\Contract\FormFieldModel */
    private $parent;

    /**
     * @param int $form_id
     * @param int $parent_id
     * @param int $field_id
     * @return mixed
     */
    public function __invoke($form_id, $parent_id, $field_id)
    {
        $this->form = Forge::form()->find($form_id, Forge::getOwnerId());

        $this->parent = $this->form->findField($parent_id);

        /** @var \Canvass\Contract\FormFieldModel $field */
        $field = Forge::field()->find($field_id);

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

        $title = $field->getData('label');
        if (empty($title)) {
            $title = $field->getData('identifier');
        }

        return Forge::success(
            sprintf('Field, %s, has been deleted.', $title),
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
