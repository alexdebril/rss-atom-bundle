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
use Debril\RssAtomBundle\Protocol\Parser\Media;

/**
 * Class Parser
 * @deprecated will be removed in version 2.0
 * @package Debril\RssAtomBundle\Protocol
 */
abstract class Parser
{

    const MEDIA_LINK_ATTIBUTE = 'href';

    /**
     * System's time zone
     *
     * @var \DateTimeZone
     */
    protected static $timezone;

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
     * @var \Debril\RssAtomBundle\Protocol\Parser\Factory
     */
    protected $factory;

    /**
     * Parses the feed's body to create a FeedContent instance.
     *
     * @param  SimpleXMLElement                             $xmlBody
     * @param  \Debril\RssAtomBundle\Protocol\FeedInterface $feed
     * @param  array                                        $filters
     * @throws Parser\ParserException
     * @return FeedInterface
     */
    public function parse(SimpleXMLElement $xmlBody, FeedInterface $feed, array $filters = array())
    {
        if (!$this->canHandle($xmlBody)) {
            throw new ParserException('this is not a supported format');
        }

        $this->checkBodyStructure($xmlBody);

        $xmlBody = $this->registerNamespaces($xmlBody);

        return $this->parseBody($xmlBody, $feed, $filters);
    }

    /**
     *
     * @param  SimpleXMLElement $body
     * @throws ParserException
     */
    protected function checkBodyStructure(SimpleXMLElement $body)
    {
        $errors = array();

        foreach ($this->mandatoryFields as $field) {
            if (!isset($body->$field)) {
                $errors[] = "missing {$field}";
            }
        }

        if (0 < count($errors)) {
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
    public function setDateFormats(array $dates)
    {
        $this->dateFormats = $dates;
    }

    /**
     *
     * @param  type            $date
     * @return string          date Format
     * @throws ParserException
     */
    public function guessDateFormat($date)
    {
        foreach ($this->dateFormats as $format) {
            $test = \DateTime::createFromFormat($format, $date);
            if ($test instanceof \DateTime)
                return $format;
        }

        throw new ParserException('Impossible to guess date format : ' . $date);
    }

    /**
     *
     * @return \Debril\RssAtomBundle\Protocol\ItemIn
     */
    public function newItem()
    {
        if ($this->getFactory() instanceof \Debril\RssAtomBundle\Protocol\Parser\Factory)
            return $this->getFactory()->newItem();

        return new Parser\Item();
    }

    /**
     *
     * @return \Debril\RssAtomBundle\Protocol\Parser\Factory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     *
     * @param  \Debril\RssAtomBundle\Protocol\Parser\Factory $factory
     * @return \Debril\RssAtomBundle\Protocol\Parser
     */
    public function setFactory(Factory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @deprecated since 1.3.0 replaced by addValidItem
     * @param  FeedIn $feed
     * @param  ItemIn $item
     * @return $this
     */
    public function addAcceptableItem(FeedIn $feed, ItemIn $item, \DateTime $modifiedSince)
    {
        $filters = array(
            new \Debril\RssAtomBundle\Protocol\Filter\ModifiedSince($modifiedSince)
        );

        return $this->addValidItem($feed, $item, $filters);
    }

    /**
     * @param  FeedIn $feed
     * @param  ItemIn $item
     * @param  array  $filters
     * @return $this
     */
    public function addValidItem(FeedIn $feed, ItemIn $item, array $filters = array())
    {
       if ( $this->isValid($item, $filters) ) {
           $feed->addItem($item);
       }

        return $this;
    }

    /**
     * @param  ItemIn $item
     * @param  array  $filters
     * @return bool
     */
    public function isValid(ItemIn $item, array $filters = array())
    {
        $valid = true;
        foreach ($filters as $filter) {
            if ($filter instanceof \Debril\RssAtomBundle\Protocol\Filter) {
                $valid = $filter->isValid($item) ? $valid : false;
            }
        }

        return $valid;
    }

    /**
     * Creates a DateTime instance for the given string. Default format is RFC2822
     *
     * @param string $string
     * @param string $format
     */
    public static function convertToDateTime($string, $format = DateTime::RFC2822)
    {
        $date = DateTime::createFromFormat($format, $string);

        if (!$date instanceof \DateTime) {
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
    public static function getSystemTimezone()
    {
        if (is_null(self::$timezone)) {
            self::$timezone = new \DateTimeZone(date_default_timezone_get());
        }

        return self::$timezone;
    }

    /**
     * Reset the system's time zone
     */
    public static function resetTimezone()
    {
        self::$timezone = null;
    }

    /**
     * register Namespaces
     *
     * @param  SimpleXMLElement $xmlBody
     * @return SimpleXMLElement
     */
    protected function registerNamespaces(SimpleXMLElement $xmlBody)
    {
        $namespaces = $xmlBody->getNamespaces(true);
        foreach ($namespaces as $prefix => $ns) {
            if ($prefix != '') {
                $xmlBody->registerXPathNamespace($prefix, $ns);
            }
        }

        return $xmlBody;
    }

    /**
     * @param  SimpleXMLElement $xmlElement
     * @param $namespaces
     * @return array
     */
    protected function getAdditionalNamespacesElements(SimpleXMLElement $xmlElement, $namespaces)
    {
        $additional = array();
        foreach ($namespaces as $prefix => $ns) {
            if ($prefix != '') {
                $additionalElement = $xmlElement->children($ns);
                if (!empty($additionalElement)) {
                    $additional[$prefix] = $additionalElement;
                }
            }
        }

        return $additional;
    }

    /**
     * @param \SimpleXMLElement $element
     * @param string $attributeName
     * @return string|null
     */
    public function getAttributeValue(SimpleXMLElement $element, $attributeName)
    {
        $attributes = $element[0]->attributes();
        foreach ( $attributes as $name => $value ) {
            if ( strcasecmp($name, $attributeName) === 0 ) {
                return $value;
            }
        }

        return null;
    }

    /**
     * @param SimpleXMLElement $element
     * @return Media
     */
    public function createMedia(SimpleXMLElement $element)
    {
        $media = new Media;
        $media->setUrl($this->getAttributeValue($element, static::MEDIA_LINK_ATTIBUTE))
              ->setType($this->getAttributeValue($element, 'type'))
              ->setLenght($this->getAttributeValue($element, 'lenght'));
    
        return $media;
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
     * @param  SimpleXMLElement $body
     * @param  FeedInterface    $feed
     * @param  array            $filters
     * @return FeedIn
     */
    abstract protected function parseBody(SimpleXMLElement $body, FeedInterface $feed, array $filters);
    
    /**
     * Handles enclosures if any
     *
     * @param  SimpleXMLElement $element
     * @param  ItemIn           $item
     * @return $this
     */
    abstract protected function handleEnclosure(SimpleXMLElement $element, ItemIn $item);
}
