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
trait PreparesFormData
{
    /**
     * @return \Canvass\Contract\FieldData[]
     */
    public function getNestedFields(): array
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
            if (0 === $item['parent_id']) {
                $level = $item['parent_id'] > 0 ? $item['parent_id'] : $item['id'];

                $nested[$level] = $item;
            }
        }

        return $nested;
    }
    
    /**
     * @param array|null $fields
     * @return array */
    public function prepareData($fields = null): array
    {
        $data = [
            'id' => $this->getId(),
            'name' => $this->getData('name'),
            'identifier' => $this->getData('identifier'),
            'classes' => $this->getData('classes'),
            'action_url' => $this->getActionUrl($this->getId()),
            'redirect_url' => $this->getData('redirect_url'),
            'introduction' => $this->getData('introduction'),
            'button_text' => $this->getData('button_text'),
            'button_classes' => $this->getData('button_classes'),
            'meta' => [],
            'fields' => $fields
        ];

        return $data;
    }
    
    /** @return \Canvass\Contract\FormFieldModel[]|null */
    abstract public function findFields();

    protected function getActionUrl($form_id): string
    {
        return Forge::getBaseUrlSegment() . $form_id;
    }
}
