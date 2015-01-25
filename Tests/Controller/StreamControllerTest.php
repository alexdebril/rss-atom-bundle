<?php

namespace Debril\RssAtomBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StreamControllerTest extends WebTestCase
{

    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', '/mock/rss');

        $response = $client->getResponse();
        $this->assertEquals('200', $response->getStatusCode());
        $lastModified = $response->getLastModified();

        $lastModified->setTimezone(
                new \DateTimeZone(date_default_timezone_get())
        );

        $lastModified->add(new \DateInterval('PT1S'));
        $this->assertInstanceOf('\DateTime', $lastModified);
        $this->assertGreaterThan(0, $response->getMaxAge());
        $this->assertGreaterThan(0, strlen($response->getContent()));
        $this->assertTrue($response->isCacheable());

        $client->request('GET', '/mock/rss', array(), array(), array('HTTP_If-Modified-Since' => $lastModified->format(\DateTime::RSS)));
        $response2 = $client->getResponse();

        $this->assertEquals('304', $response2->getStatusCode());
        $this->assertEquals(0, strlen($response2->getContent()));
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testNotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/mock/rss/not-found');
    }

    /**
     * @expectedException \Exception
     */
    public function testBadProvider()
    {
        $client = static::createClient();

        $client->request('GET', '/bad/provider');
    }

}
