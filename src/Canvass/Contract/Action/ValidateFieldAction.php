<?php

namespace Canvass\Contract\Action;

interface ValidateFieldAction
{
    public function getValidationRules(): array;

    public function validateAttributes($attributes): bool;

    public function hasValidatableAttributes(): bool;

    public function convertAttributesData($attributes): array;
}
