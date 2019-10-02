<?php

namespace Canvass\Support;

/**
 * Trait PreparesFormFieldData
 * @package Canvass\Support
 * @method getDataFromAttributes($key)
 * @method getData($key)
 * @method getHtmlType()
 */
trait PreparesFormFieldData
{
    public function prepareData(): array
    {
        return [
            'html_type' => $this->getHtmlType(),
            'name' => $this->getData('name'),
            'label' => $this->getData('label'),
            'type' => $this->getData('type'),
            'value' => $this->getData('value'),
            'identifier' => $this->getData('identifier'),
            'classes' => $this->getData('classes'),
            'help_text' => $this->getData('help_text'),
            'attributes' => $this->getData('attributes'),
            'children' => [],
            'meta' => [
                'id' => $this->getData('id'),
                'parent_id' => $this->getData('parent_id'),
                'sort' => $this->getData('sort'),
            ],
        ];
    }
}
