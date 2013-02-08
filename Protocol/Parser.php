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
use \DateTime;

use Debril\RssAtomBundle\Protocol\Parser\ParserException;
/**
 * Parser
 */
abstract class Parser
{

    protected $mandatoryFields = array();

    /**
     * Parses the feed's body to create a FeedContent instance.
     *
     * @param SimpleXMLElement $xmlBody
     * @param \DateTime $modifiedSince
     * @return FeedContent
     * @throws ParserException
     */
    public function parse(SimpleXMLElement $xmlBody, \DateTime $modifiedSince)
    {
        if ( ! $this->canHandle($xmlBody) )
        {
            throw new ParserException('this is not a supported format');
        }

        $this->checkBodyStructure($xmlBody);

        return $this->parseBody($xmlBody, $modifiedSince);
    }

    /**
     *
     * @param SimpleXMLElement $body
     * @throws ParserException
     */
    protected function checkBodyStructure(SimpleXMLElement $body)
    {
        $errors = array();

        foreach ($this->mandatoryFields as $field)
        {
            if ( ! isset($body->$field) )
            {
                $errors[] = "missing {$field}";
            }
        }

        if ( 0 < count($errors) )
        {
            $report = implode(", ", $errors);
            throw new ParserException(
                    "error while parsing the feed : {$report}"
            );
        }

    }

    /**
     * Creates a DateTime instance for the given string. Default format is RFC2822
     *
     * @param string $string
     * @param string $format
     */
    static public function convertToDateTime($string, $format = DateTime::RFC2822)
    {
        $date = DateTime::createFromFormat($format, $string);

        if ( ! $date instanceof \DateTime )
        {
            throw new ParserException("date is the wrong format : {$string} - expected {$format}");
        }

        return $date;
    }

    /**
     * Tells if the parser can handle the feed or not
     *
     * @param  SimpleXMLElement $xmlBody
     * @return boolean
     */
    abstract public function canHandle(SimpleXMLElement $xmlBody);

    /**
     * Performs the actual conversion into a FeedContent instance
     *
     * @param SimpleXMLElement $body
     * @param DateTime $modifiedSince
     * @return FeedContent
     */
    abstract protected function parseBody(SimpleXMLElement $body, \DateTime $modifiedSince);

}