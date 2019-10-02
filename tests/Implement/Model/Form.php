<?php

namespace Implement\Model;

use Canvass\Contract\FormFieldModel;
use Canvass\Contract\FormModel;
use Canvass\Support\PreparesFormData;

class Form extends Model implements FormModel
{
    use PreparesFormData;

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
        int $sort,
        $parent_id = 0
    ): ?FormFieldModel
    {
        $fields = $this->findFields($parent_id);

        foreach ($fields as $field) {
            if ($sort === (int)$field->getData('sort')) {
                return $field;
            }
        }

        return null;
    }

    public function findFieldsWithSortGreaterThan(int $sort, $parent_id = 0)
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

    protected function getActionUrl($form_id): string
    {
        return '/form/' . $form_id;
    }
}
