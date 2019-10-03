<?php

namespace Canvass\Support;

use Canvass\Contract\FormFieldModel;

final class FieldData implements \ArrayAccess
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

    public function hasAttribute($key)
    {
        return $this->field->hasAttribute($key);
    }

    /**
     * @param FormFieldModel|FieldData $field
     * @return \Canvass\Support\FieldData
     */
    public function addNestedField($field): self
    {
        if ($field instanceof FormFieldModel) {
            $field = new self($field);
        }

        if (! ($field instanceof self)) {
            $model_class = FormFieldModel::class;
            $data_class = self::class;
            throw new \InvalidArgumentException(
                "You must supply either {$model_class} or {$data_class}"
            );
        }

        $this->children[] = $field;

        return $this;
    }

    public function clearNestedFields(): void
    {
        $this->children = [];
    }

    public function toArray(): array
    {
        $data = $this->field->prepareData();

        foreach ($this->children as $child) {
            $data['children'][] = $child->__debugInfo();
        }

        return $data;
    }

    public function offsetExists($offset): bool
    {
        if ('children' === $offset) {
            return true;
        }

        return ! empty($this->field->getData($offset));
    }

    public function offsetGet($offset)
    {
        if ('children' === $offset) {
            return $this->children;
        }

        return $this->field->getData($offset);
    }

    public function offsetSet($offset, $value): void
    {
        if ('children' === $offset) {
            throw new \InvalidArgumentException(
                'Use addNestedField method instead'
            );
        }

        $this->field->setData($offset, $value);
    }

    public function offsetUnset($offset): void
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
