<?php

namespace Canvass\Contract;

interface FormFieldModel extends Model
{
    public function findAllByFormId($form_id, $parent_id = null);

    public function getHtmlType(): string;

    public function prepareData(): array;

    public function retrieveChildren();

    /**
     * Get a given attribute on the model from the attributes column.
     *
     * @param  string  $key
     * @return mixed */
    public function getDataFromAttributes($key);

    /**
     * Set a given attribute on the model in the attributes column.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed */
    public function setDataToAttributes($key, $value);
}
