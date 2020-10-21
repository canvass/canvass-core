<?php

namespace Canvass\Contract;

interface FormModel
{
    public function find($id, $owner_id = null);

    public function findAllForListing($owner_id = null);
    /**
     * @param $field_id
     * @return \Canvass\Contract\FormFieldModel|null */
    public function findField($field_id);
    /**
     * @param mixed|null $parent_id If null, return all fields
     * @return \Canvass\Contract\FormFieldModel[]|null */
    public function findFields($parent_id = null);
    /** @param int $sort
     * @param int $parent_id
     * @return \Canvass\Contract\FormFieldModel|null */
    public function findFieldWithSortOf($sort, $parent_id = 0);
    /** @param int $sort
     * @param int $parent_id
     * @return \Canvass\Contract\FormFieldModel[]|null */
    public function findFieldsWithSortGreaterThan($sort, $parent_id = 0);
    /** @return \Canvass\Contract\FormFieldModel[]|null */
    public function getNestedFields();
    /** @param \Canvass\Contract\FormFieldModel[]|null $fields
     * @return array */
    public function prepareData($fields = null);
    /** @return mixed */
    public function getId();
    /** @param string $key
     * @return mixed */
    public function getData($key);
    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed */
    public function setData($key, $value);
}
