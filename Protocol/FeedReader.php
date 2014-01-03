<?php

/**
 * Rss/Atom Bundle for Symfony 2
 *
 * @package RssAtomBundle\Protocol
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 *
 */

namespace Debril\RssAtomBundle\Protocol;

use Debril\RssAtomBundle\Protocol\Filter\ModifiedSince;
use \SimpleXMLElement;
use Debril\RssAtomBundle\Driver\HttpDriver;
use Debril\RssAtomBundle\Driver\HttpDriverResponse;
use Debril\RssAtomBundle\Protocol\Parser\Factory;
use Debril\RssAtomBundle\Protocol\Parser\ParserException;
use Debril\RssAtomBundle\Exception\FeedCannotBeReadException;
use Debril\RssAtomBundle\Exception\FeedNotFoundException;
use Debril\RssAtomBundle\Exception\FeedNotModifiedException;
use Debril\RssAtomBundle\Exception\FeedServerErrorException;
use Debril\RssAtomBundle\Exception\FeedForbiddenException;

/**
 * Class to read any kind of supported feeds (RSS, ATOM, and more if you need)
 *
 * FeedReader uses an HttpDriver to pull feeds and one more Parser instances to
 * parse them. For each feed, FeedReader automatically chooses the accurate
 * Parser and use it to return a FeedContent instance.
 *
 * <code>
 * // HttpDriver and Factory instances are required to construct a FeedReader.
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
 *
 */
class FeedReader
{

    /**
     * @var array[\Debril\RssAtomBundle\Protocol\Parser]
     */
    protected $parsers = array();

    /**
     * @var \Debril\RssAtomBundle\Driver\HttpDriver
     */
    protected $driver = null;

    /**
     * @var \Debril\RssAtomBundle\Protocol\Parser\Factory
     */
    protected $factory = null;

    /**
     * @param \Debril\RssAtomBundle\Driver\HttpDriver $driver
     * @param \Debril\RssAtomBundle\Protocol\Parser\Factory $factory
     */
    public function __construct(HttpDriver $driver, Factory $factory)
    {
        $this->driver = $driver;
        $this->factory = $factory;
    }

    /**
     * Add a Parser
     * @param \Debril\RssAtomBundle\Protocol\Parser $parser
     * @return \Debril\RssAtomBundle\Protocol\FeedReader
     */
    public function addParser(Parser $parser)
    {
        $parser->setFactory($this->factory);
        $this->parsers[] = $parser;

        return $this;
    }

    /**
     * @return \Debril\RssAtomBundle\Driver\HttpDriver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Read a feed using its url and create a FeedIn instance
     * Second parameter can be either a \DateTime instance or a numeric limit
     *
     * @param string $url
     * @param mixed $arg
     * @return \Debril\RssAtomBundle\Protocol\FeedIn
     */
    public function getFeedContent($url, $arg = null)
    {
        if (is_numeric($arg))
        {
            return $this->getFilteredContent($url, array(
                new Filter\Limit($arg)
            ));
        }
        if ($arg instanceof \DateTime)
        {
            return $this->getFeedContentSince($url, $arg);
        }

        return $this->getFilteredContent($url, array());
    }

    /**
     * @param $url
     * @param array $filters
     * @param \DateTime $modifiedSince
     * @return FeedIn
     */
    public function getFilteredContent($url, array $filters, \DateTime $modifiedSince = null)
    {
        $response = $this->getResponse($url, $modifiedSince);

        return $this->parseBody($response, $this->factory->newFeed(), $filters);
    }

    /**
     *
     * @param type $url
     * @param \DateTime $modifiedSince
     * @return type
     */
    public function getFeedContentSince($url, \DateTime $modifiedSince)
    {
        $filters = array(
            new Filter\ModifiedSince($modifiedSince)
        );
        
        return $this->getFilteredContent($url, $filters);
    }

    /**
     * Read a feed using its url and hydrate the given FeedIn instance
     * @param string $url
     * @param \Debril\RssAtomBundle\Protocol\FeedIn $feed
     * @param \DateTime $modifiedSince
     * @return \Debril\RssAtomBundle\Protocol\FeedIn
     */
    public function readFeed($url, FeedIn $feed, \DateTime $modifiedSince)
    {
        $response = $this->getResponse($url, $modifiedSince);

        $filters = array(
            new ModifiedSince($modifiedSince)
        );

        return $this->parseBody($response, $feed, $filters);
    }

    /**
     * Read the XML stream hosted at $url
     *
     * @param $url
     * @param \Datetime $modifiedSince
     * @return HttpDriverResponse
     */
    public function getResponse($url, \Datetime $modifiedSince = null)
    {
        if ( is_null($modifiedSince) ) {
            $modifiedSince = new \DateTime('@0');
        }

        return $this->getDriver()->getResponse($url, $modifiedSince);
    }

    /**
     * Parse the body of a feed and write it into the FeedIn instance
     * @todo convert the last argument into an array of filters
     * @param \Debril\RssAtomBundle\Driver\HttpDriverResponse $response
     * @param \Debril\RssAtomBundle\Protocol\FeedIn $feed
     * @param \Datetime $modifiedSince
     * @return FeedIn
     * @throws FeedNotFoundException
     * @throws FeedNotModifiedException
     * @throws FeedServerErrorException
     * @throws FeedForbiddenException
     * @throws FeedCannotBeReadException
     */
    public function parseBody(HttpDriverResponse $response, FeedIn $feed, array $filters = array())
    {
        if ($response->getHttpCodeIsOk())
        {
            $xmlBody = new SimpleXMLElement($response->getBody());
            $parser = $this->getAccurateParser($xmlBody);

            return $parser->parse($xmlBody, $feed, $filters);
        }

        switch ($response->getHttpCode())
        {
            case HttpDriverResponse::HTTP_CODE_NOT_FOUND :
                throw new FeedNotFoundException($response->getHttpMessage());
                break;
            case HttpDriverResponse::HTTP_CODE_NOT_MODIFIED :
                throw new FeedNotModifiedException($response->getHttpMessage());
                break;
            case HttpDriverResponse::HTTP_CODE_SERVER_ERROR :
                throw new FeedServerErrorException($response->getHttpMessage());
                break;
            case HttpDriverResponse::HTTP_CODE_FORBIDDEN:
                throw new FeedForbiddenException($response->getHttpMessage());
                break;
            default :
                throw new FeedCannotBeReadException($response->getHttpMessage(), $response->getHttpCode());
        }
    }

    /**
     * Choose the accurate Parser for the XML stream
     * @param SimpleXMLElement $xmlBody
     * @throws ParserException
     * @return Parser
     */
    public function getAccurateParser(SimpleXMLElement $xmlBody)
    {

        foreach ($this->parsers as $parser)
        {
            if ($parser->canHandle($xmlBody))
            {
                return $parser;
            }
        }

        throw new ParserException('No parser can handle this stream');
    }

}
