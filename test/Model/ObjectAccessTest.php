<?php
/**
 * @author Lam Kai Loon <lkloon123@hotmail.com>
 */

namespace MoceanTest\Model;

use PHPUnit\Framework\TestCase;

class ObjectAccessTest extends TestCase
{
    public function test__get()
    {
        $object = new ObjectUsedByTrait();
        $object->setResponseData([
            'test' => 'testing',
        ]);

        $this->assertEquals('testing', $object->test);
        $this->assertNull($object->dummy);
    }

    public function test__toString()
    {
        $object = new ObjectUsedByTrait();
        $this->assertEquals('this is not a response object', $object->__toString());
    }
}
