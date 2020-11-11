<?php

namespace Canvass\Support;

use Canvass\Contract\FieldDataRetrievable;
use Canvass\Forge;

/**
 * Trait PreparesFormData
 * @package Canvass\Support
 * @method getData($key)
 * @method getId(): int|string
 */
trait RetrievesNestedFieldData
{
    /**
     * @return \Canvass\Contract\FieldData[]
     */
    public function getNestedFields()
    {
        $fields = $this->findFields();

        $nested = [];

        $map = [];

        foreach ($fields as $field) {
            $data = Forge::fieldData($field);

            if ($data instanceof FieldDataRetrievable) {
                $data->retrieveAdditionalData();
            }

            $map[$field['id']] = $data;

            if ($field['parent_id'] > 0) {
                $map[$field['parent_id']]->addNestedField($data);
            }
        }

        foreach ($map as $item) {
            if (! empty($item['meta']['id'])) {
                $item_id = $item['meta']['id'];
            } else {
                $item_id = $item['id'];
            }

            if (! empty($item['meta']['id'])) {
                $parent_id = $item['meta']['parent_id'];
            } else {
                $parent_id = $item['parent_id'];
            }

            if (0 === (int) $parent_id) {
                $nested[$item_id] = $item;
            }
        }

        return $nested;
    }
    
    /** @return \Canvass\Contract\FormFieldModel[]|null */
    abstract public function findFields();
}
