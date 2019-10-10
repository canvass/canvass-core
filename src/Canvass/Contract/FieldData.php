<?php

namespace Canvass\Contract;

interface FieldData extends \ArrayAccess
{
    public function getAttribute($key);

    public function hasAttribute($key): bool;

    /**
     * @param FormFieldModel|FieldData $field
     * @return self */
    public function addNestedField($field): self;

    public function clearNestedFields(): void;

    public function toArray(): array;
}
