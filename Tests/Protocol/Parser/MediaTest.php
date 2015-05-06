<?php

namespace Debril\RssAtomBundle\Protocol\Parser;

class MediaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Debril\RssAtomBundle\Protocol\Parser\Media
     */
    protected $object;
    
    protected function setUp()
    {
        $this->object = new Media;
    }
    
    public function testSetType()
    {
        $this->object->setType('image/jpeg');
        $this->assertEquals('image/jpeg', $this->object->getType());
    }
    
    public function testSetLenght()
    {
        $this->object->setLenght('87669');
        $this->assertInternalType('integer', $this->object->getLenght());
        $this->assertEquals(87669, $this->object->getLenght());
    }

    public function testSetUrl()
    {
        $this->object->setUrl('http://localhost/');
        $this->assertEquals('http://localhost/', $this->object->getUrl());
    }

}
