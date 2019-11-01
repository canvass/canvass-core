<?php

namespace Tests;

use Canvass\Contract\Action\Action;
use Implement\Model\Form;
use Implement\Model\FormField;
use Implement\Model\Model;
use Implement\Response;
use Implement\Validate;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Model::clearAll();

        \Canvass\Forge::setFormClosure(static function () {
            return new Form();
        });

        \Canvass\Forge::setFieldClosure(static function () {
            return new FormField();
        });

        \Canvass\Forge::setResponseClosure(static function () {
            return new Response();
        });

        \Canvass\Forge::setValidatorClosure(static function () {
            return new Validate();
        });

        \Canvass\Forge::setSuccessClosure(
            static function (string $message, Action $action) {
                return new Response('success', $message);
            }
        );

        \Canvass\Forge::setErrorClosure(
            static function (string $message, Action $action) {
                return new Response('error', $message);
            }
        );

        \Canvass\Forge::setLoggerClosure(static function(\Throwable $e) {
            echo PHP_EOL . __FILE__ . ' on line ' . __LINE__ . PHP_EOL;
            echo "{$e->getMessage()} in {$e->getFile()} on {$e->getLine()}";
            echo $e->getTraceAsString();
            exit;
        });
    }
}
