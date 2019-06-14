<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 6/11/2019
 * Time: 10:24 AM.
 */

namespace MoceanTest\Model;

use PHPUnit\Framework\TestCase;

class ArrayAccessTest extends TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new ObjectUsedByTrait();
        $this->object->setResponseData([
            'test' => 'testing',
        ]);
    }

    public function testOffsetExist()
    {
        $this->assertTrue(isset($this->object['test']));
    }

    public function testOffsetGet()
    {
        $this->assertEquals($this->object['test'], 'testing');
    }

    public function testOffsetSet()
    {
        $this->object['test'] = 'another testing';
        $this->assertEquals($this->object->getResponseData()['test'], 'another testing');
    }

    public function testOffsetUnset()
    {
        unset($this->object['test']);
        $this->assertFalse(isset($this->object->getResponseData()['test']));
    }
}
