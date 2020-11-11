<?php

namespace Implement\Model;

use Canvass\Contract\FormModel;
use Canvass\Support\PreparesFormData;
use Canvass\Support\RetrievesNestedFieldData;

class Form extends Model implements FormModel
{
    use PreparesFormData, RetrievesNestedFieldData;

    protected $table = 'form';

    protected $columns = [
        'id' => null,
        'owner_id' => 1,
        'name' => 'Form',
        'introduction' => '',
        'redirect_url' => '',
        'identifier' => 'form',
        'classes' => '',
        'button_text' => 'Submit',
        'button_classes' => 'btn',
    ];

    public function findAllForListing($owner_id = null)
    {
        $forms = [];

        $data = $this->getDataFromFile();

        foreach ($data as $item) {
            if (
                null === $owner_id ||
                (int)$item['owner_id'] === (int)$owner_id
            ) {
                $forms[] = new self($item);
            }
        }

        return $forms;
    }

    public function findFieldWithSortOf(
        $sort,
        $parent_id = 0
    )
    {
        $fields = $this->findFields($parent_id);

        foreach ($fields as $field) {
            if ($sort === (int)$field->getData('sort')) {
                return $field;
            }
        }

        return null;
    }

    public function findFieldsWithSortGreaterThan($sort, $parent_id = 0)
    {
        $fields = $this->findFields($parent_id);

        $return = [];

        foreach ($fields as $field) {
            if ($sort < (int)$field->getData('sort')) {
                $return[] = $field;
            }
        }

        return $return;
    }

    public function findField($field_id)
    {
        $fields = $this->findFields();

        foreach ($fields as $field) {
            if ((int)$field_id === (int)$field->getId()) {
                return $field;
            }
        }

        throw new ModelNotFoundException('Model not found');
    }

    public function findFields($parent_id = null)
    {
        $field = new FormField();

        return $field->findAllByFormId($this->getId(), $parent_id);
    }
}
