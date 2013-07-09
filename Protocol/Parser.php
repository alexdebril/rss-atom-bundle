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
use Debril\RssAtomBundle\Protocol\Parser\Factory;

/**
 * Parser
 */
abstract class Parser
{

    /**
     * System's time zone
     *
     * @var \DateTimeZone
     */
    static protected $timezone;

    /**
     * List of mandatory fields
     *
     * @var array[string]
     */
    protected $mandatoryFields = array();

    /**
     * Feed's date format
     *
     * @var array[string]
     */
    protected $dateFormats = array();

    /**
     *
     * @var Debril\RssAtomBundle\Protocol\Parser\Factory
     */
    protected $factory;

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
        if (!$this->canHandle($xmlBody))
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
            if (!isset($body->$field))
            {
                $errors[] = "missing {$field}";
            }
        }

        if (0 < count($errors))
        {
            $report = implode(", ", $errors);
            throw new ParserException(
            "error while parsing the feed : {$report}"
            );
        }
    }

    /**
     *
     * @param array $dates
     */
    public function setdateFormats(array $dates)
    {
        $this->dateFormats = $dates;
    }

    /**
     *
     * @param type $date
     * @return string date Format
     * @throws ParserException
     */
    public function guessDateFormat($date)
    {
        foreach ($this->dateFormats as $format)
        {
            $test = \DateTime::createFromFormat($format, $date);
            if ($test instanceof \DateTime)
                return $format;
        }

        throw new ParserException('Impossible to guess date format : ' . $date);
    }

    /**
     *
     * @return \Debril\RssAtomBundle\Protocol\Parser\ParsedFeed
     */
    public function newFeed()
    {
        if ($this->getFactory() instanceof Debril\RssAtomBundle\Protocol\Parser\Factory)
            return $this->getFactory()->newItem();

        return new Parser\FeedContent;
    }

    /**
     *
     * @return \Debril\RssAtomBundle\Protocol\Parser\ParsedFeed
     */
    public function newItem()
    {
        if ($this->getFactory() instanceof Debril\RssAtomBundle\Protocol\Parser\Factory)
            return $this->getFactory()->newItem();

        return new Parser\Item;
    }

    /**
     *
     * @return Debril\RssAtomBundle\Protocol\Parser\Factory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     *
     * @param \Debril\RssAtomBundle\Protocol\Parser\Factory $factory
     * @return \Debril\RssAtomBundle\Protocol\Parser
     */
    public function setFactory(Factory $factory)
    {
        $this->factory = $factory;
        return $this;
    }

    /**
     *
     * @param \Debril\RssAtomBundle\Protocol\ParsedFeed $feed
     * @param \Debril\RssAtomBundle\Protocol\Parser\ParsedItem $item
     * @param \DateTime $startDate
     * @return \Debril\RssAtomBundle\Protocol\Parser
     * @throws \Exception
     */
    public function addAcceptableItem(ParsedFeed $feed, Parser\ParsedItem $item, \DateTime $startDate)
    {
        if ($item->getUpdated() instanceof \DateTime)
        {
            if ($item->getUpdated() > $startDate)
                $feed->addItem($item);
        }
        else
            throw new \Exception("tried to add an item without date");

        return $this;
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

        if (!$date instanceof \DateTime)
        {
            throw new ParserException("date is the wrong format : {$string} - expected {$format}");
        }

        $date->setTimezone(self::getSystemTimezone());

        return $date;
    }

    /**
     * Returns the system's timezone
     *
     * @return \DateTimeZone
     */
    static public function getSystemTimezone()
    {
        if (is_null(self::$timezone))
        {
            self::$timezone = new \DateTimeZone(date_default_timezone_get());
        }

        return self::$timezone;
    }

    /**
     * Reset the system's time zone
     */
    static public function resetTimezone()
    {
        self::$timezone = null;
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

