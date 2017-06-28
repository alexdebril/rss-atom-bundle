<?php

namespace Debril\RssAtomBundle\Tests\Controller;

use FeedIo\Reader\Document;
use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Standard\Atom;
use FeedIo\Standard\Json;
use FeedIo\Standard\Rss;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class StreamControllerTest.
 */
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

        $lastModified->add(new \DateInterval('PT10S'));
        $this->assertInstanceOf('\DateTime', $lastModified);
        $this->assertGreaterThan(0, $response->getMaxAge());
        $this->assertGreaterThan(0, strlen($response->getContent()));
        $this->assertTrue($response->isCacheable());

        $client->request('GET', '/mock/rss', array(), array(), array('HTTP_If-Modified-Since' => $lastModified->format(\DateTime::RSS)));
        $response2 = $client->getResponse();

        $this->assertEquals('304', $response2->getStatusCode());
        $this->assertEquals(0, strlen($response2->getContent()));
    }

    public function testGetAtom()
    {
        $client = static::createClient();

        $client->request('GET', '/atom/1');

        $response = $client->getResponse();
        $this->assertEquals('200', $response->getStatusCode());

        $atom = new Document($response->getContent());

        $standard = new Atom(new DateTimeBuilder(new NullLogger()));
        $this->assertTrue($standard->canHandle($atom));
    }

    public function testGetRss()
    {
        $client = static::createClient();

        $client->request('GET', '/rss/1');

        $response = $client->getResponse();
        $this->assertEquals('200', $response->getStatusCode());

        $rss = new Document($response->getContent());

        $standard = new Rss(new DateTimeBuilder(new NullLogger()));
        $this->assertTrue($standard->canHandle($rss));
    }

    public function testGetJson()
    {
        $client = static::createClient();

        $client->request('GET', '/json/1');

        $response = $client->getResponse();
        $this->assertEquals('200', $response->getStatusCode());

        $json = new Document($response->getContent());

        $standard = new Json(new DateTimeBuilder(new NullLogger()));
        $this->assertTrue($standard->canHandle($json));
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
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
