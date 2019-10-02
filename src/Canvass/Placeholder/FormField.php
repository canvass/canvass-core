<?php

namespace Canvass\Placeholder;

use Canvass\Contract\FormFieldModel;

class FormField implements FormFieldModel
{
    public function findAllByFormId($form_id, $parent_id = null)
    {
        return [new self()];
    }

    public function getHtmlType(): string
    {
        return '';
    }

    public function prepareData(): array
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
