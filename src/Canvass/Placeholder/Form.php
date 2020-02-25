<?php

namespace Canvass\Placeholder;

use Canvass\Contract\FormModel;

class Form implements FormModel
{
    public function findAllForListing($owner_id = null)
    {
        return [new self()];
    }

    public function findFields($parent_id = null)
    {
        return [new self()];
    }

    public function findField($field_id)
    {
        return new FormField();
    }
    /** @param \Canvass\Placeholder\int $sort
     * @param int $parent_id
     * @return \Canvass\Contract\FormFieldModel|null */
    public function findFieldWithSortOf($sort, $parent_id = 0)
    {
        return new self();
    }

    /** @param \Canvass\Placeholder\int $sort
     * @param int $parent_id
     * @return array|\Canvass\Contract\FormFieldModel[]|null */
    public function findFieldsWithSortGreaterThan($sort, $parent_id = 0)
    {
        return [new self()];
    }

    public function getNestedFields()
    {
        return [new self()];
    }

    public function prepareData($fields = null)
    {
        return [];
    }

    public function find($id, $owner_id = null)
    {
        return new self();
    }

    public function save()
    {
        return true;
    }

    public function delete()
    {
        return true;
    }

    public function getId()
    {
        return '';
    }

    public function getData($key)
    {
        return '';
    }

    public function setData($key, $value)
    {
        return;
    }
}
