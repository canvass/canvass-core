<?php

namespace Canvass;

use Canvass\Contract\Action;
use Canvass\Contract\FieldData;
use Canvass\Contract\FormFieldModel;
use Canvass\Contract\FormModel;
use Canvass\Contract\Response;
use Canvass\Contract\Validate;
use Canvass\Contract\ValidationMap;
use Canvass\Exception\InvalidValidationData;
use Canvass\Placeholder\Form as EmptyForm;
use Canvass\Placeholder\FormField as EmptyField;
use Canvass\Placeholder\Response as EmptyResponse;
use Canvass\Placeholder\Validate as EmptyValidate;
use Canvass\Support\FieldTypes;
use Canvass\Support\Str;
use WebAnvil\ForgeClosureNotFoundException;

final class Forge extends \WebAnvil\Forge
{
    private const RESPONSE_KEY = 'response';

    private static $owner_id;

    private static $base_url_segment = '/form/';

    private static $field_paths = [
        [
            'namespace' => __NAMESPACE__ . '\\Field',
            'path' => __DIR__ . '/Field'
        ]
    ];

    /**
     * @return \Canvass\Contract\FormModel
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function form(): FormModel
    {
        return self::handleClosure('form') ?? new EmptyForm();
    }

    /**
     * @return \Canvass\Contract\FormFieldModel
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function field(): FormFieldModel
    {
        return self::handleClosure('field') ?? new EmptyField();
    }

    /**
     * @param \Canvass\Contract\FormFieldModel $field
     * @return \Canvass\Contract\FieldData
     */
    public static function fieldData(FormFieldModel $field): FieldData
    {
        $type = $field->getData('type');

        try {
            $class = FieldTypes::getClassName($type, 'FieldData');

            return new $class($field);
        } catch (InvalidValidationData $ignore) {}

        $general_type = $field->getData('general_type');

        try {
            $class = FieldTypes::getClassName($general_type, 'FieldData');

            return new $class($field);
        } catch (InvalidValidationData $ignore) {}

        return new \Canvass\Support\FieldData($field);
    }

    private static function fieldDataClass(string $type): ?string
    {
        $paths = array_reverse(self::getFieldPaths());
        
        $type = ucfirst(Str::classSegment($type));

        foreach ($paths as $path_set) {
            $class = "{$path_set['namespace']}\\{$type}\\FieldData";

            if (class_exists($class)) {
                return $class;
            }
        }

        return null;
    }

    /**
     * @return \Canvass\Contract\Validate
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function validator(): Validate
    {
        return self::handleClosure('validate') ?? new EmptyValidate();
    }

    /**
     * @return \Canvass\Contract\ValidationMap|null
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function validationMap(): ?ValidationMap
    {
        return self::handleClosure('validation_map', false);
    }

    public static function requestData(array $fields = null): array
    {
        try {
            $closure = self::get('request');
        } catch (ForgeClosureNotFoundException $e) {
            return [];
        }

        return $closure($fields);
    }

    /**
     * @return \Canvass\Contract\Response
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function response(): Response
    {
        return self::handleClosure(self::RESPONSE_KEY) ?? new EmptyResponse();
    }

    /** @return mixed|null */
    public static function getOwnerId()
    {
       return self::$owner_id;
    }

    public static function getBaseUrlSegment(): string
    {
        return self::$base_url_segment;
    }

    /**
     * @param string $message
     * @param \Canvass\Contract\Action $action
     * @return mixed
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function success(string $message, Action $action)
    {
        try {
            $success = self::get('success');

            return $success($message, $action);
        } catch (ForgeClosureNotFoundException $ignore) {}

        $response = self::get(self::RESPONSE_KEY);

        return $response();
    }

    /**
     * @param string $message
     * @param \Canvass\Contract\Action $action
     * @return mixed
     * @throws \WebAnvil\ForgeClosureNotFoundException
     */
    public static function error(string $message, Action $action)
    {
        try {
            $error = self::get('error');

            return $error($message, $action);
        } catch (ForgeClosureNotFoundException $ignore) {}

        $response = self::get(self::RESPONSE_KEY);

        return $response();
    }

    public static function logThrowable(\Throwable $e): void
    {
        try {
            $logger = self::get('log');

            $logger($e);
        } catch (ForgeClosureNotFoundException $ignore) {}
    }

    public static function setFormClosure(\Closure $form): void
    {
        self::set('form', $form);
    }

    public static function setFieldClosure(\Closure $field): void
    {
        self::set('field', $field);
    }

    public static function setValidatorClosure(\Closure $validate): void
    {
        self::set('validate', $validate);
    }

    public static function setValidationMapClosure(\Closure $map): void
    {
        self::set('validation_map', $map);
    }

    public static function setLoggerClosure(\Closure $closure): void
    {
        self::set('log', $closure);
    }

    public static function setRequestDataClosure(\Closure $request): void
    {
        self::set('request', $request);
    }

    public static function setResponseClosure(\Closure $response): void
    {
        self::set('response', $response);
    }

    public static function setSuccessClosure(\Closure $response): void
    {
        self::set('success', $response);
    }

    public static function setErrorClosure(\Closure $response): void
    {
        self::set('error', $response);
    }

    public static function setOwnerId($owner_id): void
    {
        self::$owner_id = $owner_id;
    }

    public static function setBaseUrlSegment(string $segment): void
    {
        self::$base_url_segment =
            '/' . trim($segment, "/ \t\n\r\0\x0B") . '/';
    }

    public static function addFieldPath(string $path, string $namespace): void
    {
        self::$field_paths[] = ['namespace' => $namespace, 'path' => $path];
    }

    public static function getFieldPaths(): array
    {
        return self::$field_paths;
    }
}
