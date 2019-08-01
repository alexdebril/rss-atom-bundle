<?php declare(strict_types=1);


namespace Tests\Response;


use Debril\RssAtomBundle\Response\HeadersBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class HeadersBuilderTest extends TestCase
{

    public function testSetResponseHeadersPublic()
    {
        $builder = new HeadersBuilder();
        $response = new Response();

        $builder->setResponseHeaders($response, 'xml', new \DateTime());
        $this->assertTrue($response->headers->getCacheControlDirective('public'));
        $this->assertEquals(3600, $response->getMaxAge());
    }

    public function testSetResponseHeadersPrivate()
    {
        $builder = new HeadersBuilder(false, 30);
        $response = new Response();

        $builder->setResponseHeaders($response, 'xml', new \DateTime());
        $this->assertTrue($response->headers->getCacheControlDirective('private'));
        $this->assertEquals(30, $response->getMaxAge());
    }

    public function testSetLastModified()
    {
        $builder = new HeadersBuilder();
        $response = new Response();
        $date = new \DateTime('2018-01-01');

        $builder->setResponseHeaders($response, 'xml', $date);
        $this->assertEquals($date, $response->getLastModified());
    }


}
