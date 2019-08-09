<?php


namespace Tests\Request;


use Debril\RssAtomBundle\Request\ModifiedSince;
use PHPUnit\Framework\TestCase;
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

        $modifiedSince = new ModifiedSince($stack);
        $this->assertEquals($date, $modifiedSince->getValue());
    }

}
