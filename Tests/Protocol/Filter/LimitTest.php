<?php

namespace Debril\RssAtomBundle\Protocol\Filter;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-02-12 at 23:05:01.
 */
class LimitTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Limit
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Limit(2);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Filter\Limit::isValid
     * @todo   Implement testIsValid().
     */
    public function testIsValid()
    {
        $item = new \Debril\RssAtomBundle\Protocol\Parser\Item();
        $this->assertTrue($this->object->isValid($item), 'First one is valid');
        $this->assertTrue($this->object->isValid($item), 'Second one is valid');
        $this->assertFalse($this->object->isValid($item), 'Third one is not valid');
    }

}
