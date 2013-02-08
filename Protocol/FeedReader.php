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

use \SimpleXMLElement;

use Debril\RssAtomBundle\Driver\HttpDriver;
use Debril\RssAtomBundle\Driver\HttpDriverResponse;
use Debril\RssAtomBundle\Protocol\Parser\ParserException;
use Debril\RssAtomBundle\Protocol\FeedCannotBeReadException;

class FeedReader
{

    /**
     *
     * @var type
     */
    protected $parsers = array();

    /**
     *
     * @var Debril\RssAtomBundle\Driver\Driver
     */
    protected $driver = null;

    /**
     *
     * @param \Debril\RssAtomBundle\Driver\HttpDriver $driver
     */
    public function __construct( HttpDriver $driver )
    {
        $this->driver = $driver;
    }

    /**
     * @param Parser $parser
     * @return \Debril\RssAtomBundle\Protocol\FeedReader
     */
    public function addParser(Parser $parser)
    {
        $this->parsers[] = $parser;

        return $this;
    }

    /**
     *
     * @return \Debril\RssAtomBundle\Driver\HttpDriver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     *
     * @param string $url
     * @param \DateTime $lastModified
     * @return \Debril\RssAtomBundle\Protocol\FeedContent
     */
    public function getFeedContent($url, \DateTime $modifiedSince)
    {
        $response = $this->getResponse($url, $modifiedSince);

        return $this->parseBody($response, $modifiedSince);
    }

    /**
     *
     * @param string $url
     * @param \Datetime $lastModified
     * @return \Debril\RssAtomBundle\Driver\HttpDriverResponse
     */
    public function getResponse($url, \Datetime $modifiedSince)
    {
        return $this->getDriver()->getResponse($url, $modifiedSince);
    }

    /**
     *
     * @param \Debril\RssAtomBundle\Driver\HttpDriverResponse $response
     * @param \Datetime $modifiedSince
     * @return FeedContent
     * @throws FeedCannotBeReadException
     */
    public function parseBody(HttpDriverResponse $response, \Datetime $modifiedSince)
    {
        if ( $response->getHttpCodeIsOk() )
        {
            $xmlBody = new SimpleXMLElement($response->getBody());
            $parser = $this->getAccurateParser($xmlBody);

            return $parser->parse($xmlBody, $modifiedSince);
        }

        throw new FeedCannotBeReadException($response->getHttpMessage(), $response->getHttpCode());
    }

    /**
     * @param SimpleXMLElement $xmlBody
     * @throws ParserException
     * @return Parser
     */
    public function getAccurateParser(SimpleXMLElement $xmlBody)
    {

        foreach ($this->parsers as $parser)
        {
            if ( $parser->canHandle($xmlBody) )
            {
                return $parser;
            }
        }

        throw new ParserException('No parser can handle this stream');
    }

}