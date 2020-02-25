<?php

namespace Canvass\Placeholder;

use Canvass\Contract\FormFieldModel;
use Canvass\Contract\FormModel;

class FormField implements FormFieldModel
{
    public function findAllByFormId($form_id, $parent_id = null)
    {
        return [new self()];
    }
    /** @return string */
    public function getHtmlType()
    {
        return '';
    }
    /** @return string */
    public function getGeneralType()
    {
        return '';
    }
    /** @return array */
    public function prepareData()
    {
        return [];
    }

    public function retrieveChildren()
    {
        return [new self()];
    }

    public function getDataFromAttributes($key)
    {
        return '';
    }

    public function setDataToAttributes($key, $value)
    {
        return;
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

    public function hasAttribute($key): bool
    {
        return false;
    }
    /** @return \Canvass\Contract\FormModel */
    public function getFormModel()
    {
        return new Form();
    }
    /** @param \Canvass\Contract\FormModel
     * @return void */
    public function setFormModel(FormModel $form_model)
    {
        return;
    }
}
