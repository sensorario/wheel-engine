<?php

namespace Sensorario\Tests\WheelEngine;

use PHPUnit_Framework_TestCase;
use Sensorario\WheelEngine\Engine;

class EngineTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function test()
    {
        $engine = new Engine();
        $engine->render('foo',[]);
    }
}
