<?php


namespace Tests\Request;


use Debril\RssAtomBundle\Request\ModifiedSince;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ModifiedSinceTest extends TestCase
{

    public function testGetValue()
    {
        $stack = new RequestStack();
        $request = new Request();
        $date = new \DateTime('2018-06-01');
        $request->headers->set('If-Modified-Since', $date->format(\DATE_RSS));
        $stack->push($request);

        $modifiedSince = new ModifiedSince($stack, new NullLogger());
        $this->assertEquals($date, $modifiedSince->getValue());
    }

    public function testGetBadValue()
    {
        $stack = new RequestStack();
        $request = new Request();
        $request->headers->set('If-Modified-Since', 'something that is not a date');
        $stack->push($request);

        $modifiedSince = new ModifiedSince($stack, new NullLogger());
        $this->assertInstanceOf('\DateTime', $modifiedSince->getValue());
    }

    public function testGetEmptyArrayValue()
    {
        $stack = new RequestStack();
        $request = new Request();
        $request->headers->set('If-Modified-Since', array());
        $stack->push($request);

        $modifiedSince = new ModifiedSince($stack, new NullLogger());
        $this->assertInstanceOf('\DateTime', $modifiedSince->getValue());
    }


    public function testGetValueInArray()
    {
        $stack = new RequestStack();
        $request = new Request();
        $date = new \DateTime('2018-06-01');
        $request->headers->set('If-Modified-Since', [$date->format(\DATE_RSS)]);
        $stack->push($request);

        $modifiedSince = new ModifiedSince($stack, new NullLogger());
        $this->assertEquals($date, $modifiedSince->getValue());
    }

}
