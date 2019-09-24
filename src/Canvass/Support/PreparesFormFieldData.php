<?php

namespace Canvass\Support;

/**
 * Trait PreparesFormFieldData
 * @package Canvass\Support
 * @method getAttribute($key)
 * @method getHtmlType()
 */
trait PreparesFormFieldData
{
    public function prepareData(): array
    {
        $options = $this->getAttribute('options');

        if (is_string($options) && strpos($options, ',') !== false) {
            $options = explode(',', $options);

            foreach ($options as $key => $option) {
                $options[$key] = trim($option);
            }
        }

        return [
            'html_type' => $this->getHtmlType(),
            'name' => $this->getAttribute('name'),
            'label' => $this->getAttribute('label'),
            'type' => $this->getAttribute('type'),
            'value' => $this->getAttribute('value'),
            'identifier' => $this->getAttribute('identifier'),
            'classes' => $this->getAttribute('classes'),
            'help_text' => $this->getAttribute('help_text'),
            'options' => $options,
            'children' => [],
            'meta' => [
                'id' => $this->getAttribute('id'),
                'parent_id' => $this->getAttribute('parent_id'),
                'sort' => $this->getAttribute('sort'),
            ],
        ];
    }
}
