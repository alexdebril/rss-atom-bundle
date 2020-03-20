<?php

namespace Debril\RssAtomBundle\Tests\Response;

use Debril\RssAtomBundle\Request\ModifiedSince;
use Debril\RssAtomBundle\Response\FeedBuilder;
use Debril\RssAtomBundle\Response\HeadersBuilder;
use FeedIo\Factory;
use FeedIo\Feed;
use FeedIo\FeedIo;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class FeedBuilderTest extends TestCase
{

    /**
     * @var FeedIo
     */
    private $feedIo;

    /**
     * @var HeadersBuilder
     */
    private $headersBuilder;

    /**
     * @var ModifiedSince
     */
    private $modifiedSince;

    public function setUp()
    {
        $stack = new RequestStack();
        $request = new Request();

        $stack->push($request);
        $this->feedIo = Factory::create()->getFeedIo();
        $this->headersBuilder = new HeadersBuilder();
        $this->modifiedSince = new ModifiedSince($stack, new NullLogger());
        parent::setUp();
    }

    public function testGetResponse()
    {
        $feedBuilder = new FeedBuilder($this->feedIo, $this->headersBuilder, $this->modifiedSince);

        $response = $feedBuilder->getResponse('atom', $this->getFeed());
        $this->assertEquals('200', $response->getStatusCode());
    }

    private function getFeed(): Feed
    {
        $feed = new Feed();
        $feed->setTitle('rss-atom');
        $feed->setPublicId('http://public-id');
        $feed->setLink('http://link');
        $feed->setLastModified(new \DateTime('2018-06-01'));

        $item = new Feed\Item();
        $item->setLastModified(new \DateTime('2018-06-01'));
        $item->setDescription("lorem ipsum");
        $item->setTitle('title');

        $feed->add($item);

        return $feed;
    }
}
