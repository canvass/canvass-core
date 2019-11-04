<?php

namespace Canvass\Contract;

interface FormFieldModel
{
    public function find($id, $owner_id = null);

    public function findAllByFormId($form_id, $parent_id = null);

    public function getGeneralType(): string;

    public function prepareData(): array;

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

    public function hasAttribute($key): bool;

    /**
     * Set a given attribute on the model in the attributes column.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed */
    public function setDataToAttributes($key, $value);

    public function getFormModel(): FormModel;

    public function setFormModel(FormModel $form_model): void;
}
