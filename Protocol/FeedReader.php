<?php
/**
 * Rss/Atom Bundle for Symfony 2
 *
 * @package RssBundle\Protocol
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 *
 */
namespace Debril\RssBundle\Protocol;

use \SimpleXMLElement;
use Debril\RssBundle\Driver\HttpDriver;
use Debril\RssBundle\Protocol\Parser\ParserException;

class FeedReader
{

    /**
     *
     * @var type
     */
    protected $parsers = array();

    /**
     *
     * @var Debril\RssBundle\Driver\Driver
     */
    protected $driver = null;

    /**
     *
     * @param \Debril\RssBundle\Driver\HttpDriver $driver
     */
    public function __construct( HttpDriver $driver )
    {
        $this->driver = $driver;
    }

    /**
     * @param Parser $parser
     * @return \Debril\RssBundle\Protocol\FeedReader
     */
    public function addParser(Parser $parser)
    {
        $this->parsers[] = $parser;

        return $this;
    }

    /**
     *
     * @return \Debril\RssBundle\Driver\HttpDriver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     *
     * @param string $url
     * @param \DateTime $lastModified
     * @return \Debril\RssBundle\Protocol\FeedContent
     */
    public function getFeedContent($url, \DateTime $lastModified)
    {
        $response = $this->getResponse($url, $lastModified);

        return $this->parseBody($response);
    }

    /**
     *
     * @param string $url
     * @param \Datetime $lastModified
     * @return \Debril\RssBundle\Driver\HttpDriverResponse
     */
    public function getResponse($url, \Datetime $lastModified)
    {
        return $this->getDriver()->getResponse($url, $lastModified);
    }

    /**
     * @param HttpMessage $message
     * @return \Debril\RssBundle\Protocol\FeedContent
     */
    public function parseBody(\Debril\RssBundle\Driver\HttpDriverResponse $response)
    {
        $xmlBody = new SimpleXMLElement($response->getBody());
        $parser = $this->getAccurateParser($xmlBody);

        return $parser->parse($xmlBody);
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