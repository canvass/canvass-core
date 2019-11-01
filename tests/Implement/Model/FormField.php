<?php

namespace Implement\Model;

use Canvass\Contract\FormFieldModel;
use Canvass\Contract\FormModel;
use Canvass\Support\PreparesFormFieldData;

class FormField extends Model implements FormFieldModel
{
    use PreparesFormFieldData;

    protected $table = 'field';

    /** @var FormModel */
    protected $form_model;

    protected $columns = [
        'id' => null,
        'form_id' => 1,
        'parent_id' => 0,
        'sort' => 1,
        'identifier' => 'field',
        'classes' => 'form-control',
        'name' => 'field',
        'label' => 'Field',
        'type' => 'text',
        'general_type' => 'input',
        'value' => '',
        'help_text' => '',
        'attributes' => []
    ];

    public function findAllByFormId($form_id, $parent_id = null)
    {
        $fields = [];

        $data = $this->getDataFromFile();

        foreach ($data as $item) {
            if ((int)$item['form_id'] === (int)$form_id) {
                if (null === $parent_id) {
                    $fields[] = new self($item);
                } elseif ((int)$parent_id === (int)$item['parent_id']) {
                    $fields[] = new self($item);
                }
            }
        }

        return $fields;
    }

    public function getHtmlType(): string
    {
        return $this->data['general_type'];
    }

    public function retrieveChildren()
    {
        $fields = [];

        $data = $this->getDataFromFile();

        foreach ($data as $item) {
            if (
                (int)$item['form_id'] === (int)$this->getData('form_id') &&
                (int) $item['parent_id'] > (int) $this->getData('parent_id')
            ) {
                $fields[] = new self($item);
            }
        }

        return $fields;
    }

    public function getDataFromAttributes($key)
    {
        return $this->data['attributes'][$key] ?? '';
    }

    public function hasAttribute($key): bool
    {
        return isset($this->data['attributes'][$key]);
    }

    public function setDataToAttributes($key, $value)
    {
        $this->data['attributes'][$key] = $value;
    }

    public function getFormModel(): FormModel
    {
        return $this->form_model;
    }

    public function setFormModel(FormModel $form_model): void
    {
        $this->form_model = $form_model;
    }

    public function __debugInfo()
    {
        return $this->data;
    }
}
