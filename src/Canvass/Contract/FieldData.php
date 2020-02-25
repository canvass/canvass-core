<?php

namespace Canvass\Contract;

interface FieldData extends \ArrayAccess
{
    public function getAttribute($key);
    /** @param $key
     * @return bool */
    public function hasAttribute($key);
    /**
     * @param FormFieldModel|FieldData $field
     * @return self */
    public function addNestedField($field);
    /** @return void */
    public function clearNestedFields();
    /** @return array */
    public function toArray();
}
