<?php

namespace Canvass\Action\Validation\FormField;

interface InterfaceValidateField
{
    public function getValidationRules(): array;

    public function validateAttributes($attributes): bool;

    public function hasValidatableAttributes(): bool;

    public function convertAttributesData($attributes): array;
}
