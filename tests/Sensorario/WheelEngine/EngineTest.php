<?php

namespace Sensorario\Tests\WheelEngine;

use PHPUnit_Framework_TestCase;
use Sensorario\WheelEngine\Engine;

class EngineTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->engine = new Engine();
    }

    /**
     * @expectedException        \RuntimeException
     * @expectedExceptionMessage Template foo not exists!
     */
    public function testWheneverCalledWithWrongTemplateNameThrowAnException()
    {
        $this->engine->render('foo', []);
    }

    public function testTransformArrayKeyValuesInPhpVariables()
    {
        $this->engine->buildGlobalVars($variables = [
            'foo' => 'bar',
        ]);

        $this->assertEquals(
            '<?php $foo = "bar"; ?>',
            $this->engine->getGlobalVars()
        );
    }
}
