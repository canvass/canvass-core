<?php

namespace Canvass\Support;

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
     * @param array|null $fields
     * @return array */
    public function prepareData($fields = null)
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

    /**
     * @param $form_id
     * @return string */
    protected function getActionUrl($form_id)
    {
        return Forge::getBaseUrlSegment() . $form_id;
    }
}
