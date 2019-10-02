<?php

namespace Canvass\Placeholder;

use Canvass\Contract\FormFieldModel;
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

    public function findFieldWithSortOf(
        int $sort,
        $parent_id = 0
    ): ?FormFieldModel {
        return new self();
    }

    public function findFieldsWithSortGreaterThan(int $sort, $parent_id = 0)
    {
        return [new self()];
    }

    public function getNestedFields()
    {
        return [new self()];
    }

    public function prepareData($fields = null): array
    {
        return [];
    }

    public function find($id)
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
