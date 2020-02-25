<?php

namespace Canvass\Contract;

interface FormFieldModel
{
    public function find($id, $owner_id = null);

    public function findAllByFormId($form_id, $parent_id = null);
    /** @return string */
    public function getGeneralType();
    /** @return array */
    public function prepareData();

    public function retrieveChildren();
    /**
     * @return mixed
     */
    public function getId();
    /**
     * Get a given attribute on the model.
     *
     * @param  string  $key
     * @return mixed */
    public function getData($key);
    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed */
    public function setData($key, $value);
    /**
     * Get a given attribute on the model from the attributes column.
     *
     * @param  string  $key
     * @return mixed */
    public function getDataFromAttributes($key);
    /** @param $key
     * @return bool */
    public function hasAttribute($key);
    /**
     * Set a given attribute on the model in the attributes column.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed */
    public function setDataToAttributes($key, $value);
    /** @return \Canvass\Contract\FormModel */
    public function getFormModel();
    /** @param \Canvass\Contract\FormModel $form_model
     * @return void */
    public function setFormModel(FormModel $form_model);
}
