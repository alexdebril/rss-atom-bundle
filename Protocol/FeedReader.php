<?php

/**
 * Rss/Atom Bundle for Symfony.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol;

use Debril\RssAtomBundle\Protocol\Filter\ModifiedSince;
use Debril\RssAtomBundle\Protocol\Parser\XmlParser;
use SimpleXMLElement;
use Debril\RssAtomBundle\Driver\HttpDriverInterface;
use Debril\RssAtomBundle\Driver\HttpDriverResponse;
use Debril\RssAtomBundle\Protocol\Parser\Factory;
use Debril\RssAtomBundle\Exception\ParserException;
use Debril\RssAtomBundle\Exception\FeedException\FeedCannotBeReadException;
use Debril\RssAtomBundle\Exception\FeedException\FeedNotFoundException;
use Debril\RssAtomBundle\Exception\FeedException\FeedNotModifiedException;
use Debril\RssAtomBundle\Exception\FeedException\FeedServerErrorException;
use Debril\RssAtomBundle\Exception\FeedException\FeedForbiddenException;

/**
 * Class to read any kind of supported feeds (RSS, ATOM, and more if you need).
 *
 * FeedReader uses an HttpDriverInterface to pull feeds and one more Parser instances to
 * parse them. For each feed, FeedReader automatically chooses the accurate
 * Parser and use it to return a FeedContent instance.
 *
 * <code>
 * // HttpDriverInterface and Factory instances are required to construct a FeedReader.
 * // Here we use the HttpCurlDriver (recommanded)
 * $reader = new FeedReader(new HttpCurlDriver(), new Factory());
 *
 * // now we add the parsers
 * $reader->addParser(new AtomParser());
 * $reader->addParser(new RssParser());
 *
 * // $url is obviously the feed you want to read
 * // $dateTime is the last moment you read the feed
 * $content = $reader->getFeedContent($url, $dateTime);
 *
 * // now we can display the feed's content
 * echo $feed->getTitle();
 *
 * // each
 * foreach( $content->getItems() as $item )
 * {
 *      echo $item->getTitle();
 *      echo $item->getSummary();
 * }
 * </code>
 * @deprecated removed in version 3.0
 */
class FeedReader
{
    /**
     * @var Parser[]
     */
    protected $parsers = array();

    /**
     * @var HttpDriverInterface
     */
    protected $driver = null;

    /**
     * @var Factory
     */
    protected $factory = null;

    /**
     * @var XmlParser
     */
    protected $xmlParser = null;

    /**
     * @param HttpDriverInterface $driver
     * @param Factory             $factory
     * @deprecated removed in version 3.0
     */
    public function __construct(HttpDriverInterface $driver, Factory $factory, XmlParser $xmlParser)
    {
        $this->driver = $driver;
        $this->factory = $factory;
        $this->xmlParser = $xmlParser;
    }

    /**
     * Add a Parser.
     *
     * @param Parser $parser
     *
     * @return FeedReader
     * @deprecated removed in version 3.0
     */
    public function addParser(Parser $parser)
    {
        $parser->setFactory($this->factory);
        $this->parsers[] = $parser;

        return $this;
    }

    /**
     * @return HttpDriverInterface
     * @deprecated removed in version 3.0
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Read a feed using its url and create a FeedInInterface instance
     * Second parameter can be either a \DateTime instance or a numeric limit.
     *
     * @param string    $url
     * @param \DateTime $arg
     *
     * @return FeedInInterface|FeedContent
     * @deprecated removed in version 3.0
     */
    public function getFeedContent($url, $arg = null)
    {
        if (is_numeric($arg)) {
            return $this->getFilteredContent($url, array(
                        new Filter\Limit($arg),
            ));
        }
        if ($arg instanceof \DateTime) {
            return $this->getFeedContentSince($url, $arg);
        }

        return $this->getFilteredContent($url, array());
    }

    /**
     * @param string    $url
     * @param array     $filters
     * @param \DateTime $modifiedSince
     *
     * @return FeedInInterface
     * @deprecated removed in version 3.0
     */
    public function getFilteredContent($url, array $filters, \DateTime $modifiedSince = null)
    {
        $response = $this->getResponse($url, $modifiedSince);

        return $this->parseBody($response, $this->factory->newFeed(), $filters);
    }

    /**
     * @param string    $url
     * @param \DateTime $modifiedSince
     *
     * @return FeedInInterface
     * @deprecated removed in version 3.0
     */
    public function getFeedContentSince($url, \DateTime $modifiedSince)
    {
        $filters = array(
            new Filter\ModifiedSince($modifiedSince),
        );

        return $this->getFilteredContent($url, $filters);
    }

    /**
     * Read a feed using its url and hydrate the given FeedInInterface instance.
     *
     * @param string          $url
     * @param FeedInInterface $feed
     * @param \DateTime       $modifiedSince
     *
     * @return FeedInInterface
     * @deprecated removed in version 3.0
     */
    public function readFeed($url, FeedInInterface $feed, \DateTime $modifiedSince)
    {
        $response = $this->getResponse($url, $modifiedSince);

        $filters = array(
            new ModifiedSince($modifiedSince),
        );

        return $this->parseBody($response, $feed, $filters);
    }

    /**
     * Read the XML stream hosted at $url.
     *
     * @param $url
     * @param \Datetime $modifiedSince
     *
     * @return HttpDriverResponse
     * @deprecated removed in version 3.0
     */
    public function getResponse($url, \Datetime $modifiedSince = null)
    {
        if (is_null($modifiedSince)) {
            $modifiedSince = new \DateTime('@0');
        }

        return $this->getDriver()->getResponse($url, $modifiedSince);
    }

    /**
     * Parse the body of a feed and write it into the FeedInInterface instance.
     *
     * @param HttpDriverResponse $response
     * @param FeedInInterface    $feed
     * @param array              $filters
     *
     * @return FeedInInterface
     * @deprecated removed in version 3.0
     */
    public function parseBody(HttpDriverResponse $response, FeedInInterface $feed, array $filters = array())
    {
        if ($response->getHttpCodeIsOk()
            || $response->getHttpCodeIsRedirection()) {
            $xmlBody = $this->xmlParser->parseString($response->getBody());
            $parser = $this->getAccurateParser($xmlBody);

            return $parser->parse($xmlBody, $feed, $filters);
        }

        switch ($response->getHttpCode()) {
            case HttpDriverResponse::HTTP_CODE_NOT_FOUND:
                throw new FeedNotFoundException($response->getHttpMessage());
            case HttpDriverResponse::HTTP_CODE_NOT_MODIFIED:
                throw new FeedNotModifiedException($response->getHttpMessage());
            case HttpDriverResponse::HTTP_CODE_SERVER_ERROR:
                throw new FeedServerErrorException($response->getHttpMessage());
            case HttpDriverResponse::HTTP_CODE_FORBIDDEN:
                throw new FeedForbiddenException($response->getHttpMessage());
            default:
                throw new FeedCannotBeReadException($response->getHttpMessage(), $response->getHttpCode());
        }
    }

    /**
     * Choose the accurate Parser for the XML stream.
     *
     * @param SimpleXMLElement $xmlBody
     *
     * @throws ParserException
     *
     * @return Parser
     * @deprecated removed in version 3.0
     */
    public function getAccurateParser(SimpleXMLElement $xmlBody)
    {
        foreach ($this->parsers as $parser) {
            if ($parser->canHandle($xmlBody)) {
                return $parser;
            }
        }

        throw new ParserException('No parser can handle this stream');
    }
}
