<?php

namespace Canvass\Support;

/**
 * Trait PreparesFormData
 * @package Canvass\Support
 * @method getAttribute($key)
 * @method getId(): int|string
 */
trait PreparesFormData
{
    public function getNestedFields()
    {
        $fields = $this->findFields();
        
        $nested = [];

        foreach ($fields as $field) {
            $prepared = $field->prepareData();

            $level = $field['parent_id'] > 0 ? $field['parent_id'] : $field['id'];
            
            if (empty($nested[$level])) {
                $prepared['children'] = [];
                
                $nested[$level] = $prepared;
            } else {
                $nested[$level]['children'][] = $prepared;
            }
        }
        
        return $nested;
    }
    
    /**
     * @param array|null $fields
     * @return array */
    public function prepareData($fields = null, $csrf_token = null): array
    {
        $data = [
            'csrf_token' => $csrf_token,
            'html_type' => 'form',
            'id' => $this->getId(),
            'identifier' => $this->getAttribute('identifier'),
            'classes' => $this->getAttribute('classes'),
            'action_url' => $this->getActionUrl($this->getId()),
            'redirect_url' => $this->getAttribute('redirect_url'),
            'introduction' => $this->getAttribute('introduction'),
            'button_text' => $this->getAttribute('button_text'),
            'button_classes' => $this->getAttribute('button_classes'),
            'fields' => $fields
        ];

        return $data;
    }
    
    /** @return \Canvass\Contract\FormFieldModel[]|null */
    abstract public function findFields();

    abstract protected function getActionUrl($form_id): string;
}
