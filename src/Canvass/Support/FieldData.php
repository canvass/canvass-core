<?php

namespace Canvass\Support;

use Canvass\Contract\FormFieldModel;

class FieldData implements \Canvass\Contract\FieldData
{
    /** @var \Canvass\Contract\FormFieldModel */
    private $field;
    /** @var \Canvass\Support\FieldData[] */
    private $children = [];

    public function __construct(FormFieldModel $field)
    {
        $this->field = $field;
    }

    public function getAttribute($key)
    {
        return $this->field->getDataFromAttributes($key);
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasAttribute($key)
    {
        return $this->field->hasAttribute($key);
    }

    /**
     * @param \Canvass\Contract\FieldData|\Canvass\Contract\FormFieldModel $field
     * @return \Canvass\Contract\FieldData
     */
    public function addNestedField($field)
    {
        if ($field instanceof \Canvass\Contract\FormFieldModel) {
            $field = new self($field);
        }

        if (! ($field instanceof \Canvass\Contract\FieldData)) {
            $model_class = FormFieldModel::class;
            $data_class = self::class;
            throw new \InvalidArgumentException(
                "You must supply either {$model_class} or {$data_class}"
            );
        }

        $this->children[] = $field;

        return $this;
    }

    /** @return void */
    public function clearNestedFields()
    {
        $this->children = [];
    }

    /** @return \Canvass\Contract\FormFieldModel */
    public function getField()
    {
        return $this->field;
    }

    /** @return array */
    public function toArray()
    {
        $data = $this->field->prepareData();

        foreach ($this->children as $child) {
            $data['children'][] = $child->__debugInfo();
        }

        return $data;
    }

    /**
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        if ('children' === $offset) {
            return true;
        }

        return ! empty($this->field->getData($offset));
    }

    /**
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if ('children' === $offset) {
            return $this->children;
        }

        return $this->field->getData($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if ('children' === $offset) {
            throw new \InvalidArgumentException(
                'Use addNestedField method instead'
            );
        }

        $this->field->setData($offset, $value);
    }

    /**
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        if ('children' === $offset) {
            throw new \InvalidArgumentException(
                'Use clearNestedFields method instead'
            );
        }

        $this->field->setData($offset, null);
    }

    public function __debugInfo()
    {
        return $this->toArray();
    }
}
