<?php

/**
 * Rss/Atom Bundle for Symfony 2.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol;

use Debril\RssAtomBundle\Protocol\Filter\ModifiedSince;
use Debril\RssAtomBundle\Protocol\Parser\Item;
use Debril\RssAtomBundle\Exception\ParserException;
use Debril\RssAtomBundle\Protocol\Parser\Factory;
use Debril\RssAtomBundle\Protocol\Parser\Media;

/**
 * Class Parser.
 */
abstract class Parser
{
    /**
     * System's time zone.
     *
     * @var \DateTimeZone
     */
    protected static $timezone;

    /**
     * List of mandatory fields.
     *
     * @var string[]
     */
    protected $mandatoryFields = array();

    /**
     * Feed's date format.
     *
     * @var string[]
     */
    protected $dateFormats = array();

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * Parses the feed's body to create a FeedContent instance.
     *
     * @param \SimpleXMLElement                            $xmlBody
     * @param \Debril\RssAtomBundle\Protocol\FeedInterface $feed
     * @param array                                        $filters
     *
     * @throws ParserException
     *
     * @return FeedInterface
     */
    public function parse(\SimpleXMLElement $xmlBody, FeedInterface $feed, array $filters = array())
    {
        if (!$this->canHandle($xmlBody)) {
            throw new ParserException('this is not a supported format');
        }

        $this->checkBodyStructure($xmlBody);

        $xmlBody = $this->registerNamespaces($xmlBody);

        return $this->parseBody($xmlBody, $feed, $filters);
    }

    /**
     * @param \SimpleXMLElement $body
     *
     * @throws ParserException
     */
    protected function checkBodyStructure(\SimpleXMLElement $body)
    {
        $errors = array();

        foreach ($this->mandatoryFields as $field) {
            if (!isset($body->$field)) {
                $errors[] = "missing {$field}";
            }
        }

        if (0 < count($errors)) {
            $report = implode(', ', $errors);
            throw new ParserException(
                "error while parsing the feed : {$report}"
            );
        }
    }

    /**
     * @param array $dates
     */
    public function setDateFormats(array $dates)
    {
        $this->dateFormats = $dates;
    }

    /**
     * @param string $date
     *
     * @return string date Format
     *
     * @throws ParserException
     */
    public function guessDateFormat($date)
    {
        foreach ($this->dateFormats as $format) {
            $test = \DateTime::createFromFormat($format, $date);
            if ($test instanceof \DateTime) {
                return $format;
            }
        }

        throw new ParserException('Impossible to guess date format : '.$date);
    }

    /**
     * @return ItemInInterface
     */
    public function newItem()
    {
        if ($this->getFactory() instanceof Factory) {
            return $this->getFactory()->newItem();
        }

        return new Item();
    }

    /**
     * @return Factory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @param Factory $factory
     *
     * @return Parser
     */
    public function setFactory(Factory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @deprecated since 1.3.0 replaced by addValidItem
     *
     * @param FeedInInterface $feed
     * @param ItemInInterface $item
     * @param \DateTime       $modifiedSince
     *
     * @return $this
     */
    public function addAcceptableItem(FeedInInterface $feed, ItemInInterface $item, \DateTime $modifiedSince)
    {
        $filters = array(
            new ModifiedSince($modifiedSince),
        );

        return $this->addValidItem($feed, $item, $filters);
    }

    /**
     * @param FeedInInterface $feed
     * @param ItemInInterface $item
     * @param array           $filters
     *
     * @return $this
     */
    public function addValidItem(FeedInInterface $feed, ItemInInterface $item, array $filters = array())
    {
        if ($this->isValid($item, $filters)) {
            $feed->addItem($item);
        }

        return $this;
    }

    /**
     * @param ItemInInterface $item
     * @param array           $filters
     *
     * @return bool
     */
    public function isValid(ItemInInterface $item, array $filters = array())
    {
        $valid = true;
        foreach ($filters as $filter) {
            if ($filter instanceof FilterInterface) {
                $valid = $filter->isValid($item) ? $valid : false;
            }
        }

        return $valid;
    }

    /**
     * Creates a DateTime instance for the given string. Default format is RFC2822.
     *
     * @param string $string
     * @param string $format
     *
     * @return \DateTime
     */
    public static function convertToDateTime($string, $format = \DateTime::RFC2822)
    {
        $date = \DateTime::createFromFormat($format, $string);

        if (!$date instanceof \DateTime) {
            throw new ParserException("date is the wrong format : {$string} - expected {$format}");
        }

        $date->setTimezone(static::getSystemTimezone());

        return $date;
    }

    /**
     * Returns the system's timezone.
     *
     * @return \DateTimeZone
     */
    public static function getSystemTimezone()
    {
        if (is_null(static::$timezone)) {
            static::$timezone = new \DateTimeZone(date_default_timezone_get());
        }

        return static::$timezone;
    }

    /**
     * Reset the system's time zone.
     */
    public static function resetTimezone()
    {
        static::$timezone = null;
    }

    /**
     * register Namespaces.
     *
     * @param \SimpleXMLElement $xmlBody
     *
     * @return \SimpleXMLElement
     */
    protected function registerNamespaces(\SimpleXMLElement $xmlBody)
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
     * @param \SimpleXMLElement $xmlElement
     * @param array             $namespaces
     *
     * @return array
     */
    protected function getAdditionalNamespacesElements(\SimpleXMLElement $xmlElement, $namespaces)
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
     * @param string            $attributeName
     *
     * @return string|null
     */
    public function getAttributeValue(\SimpleXMLElement $element, $attributeName)
    {
        $attributes = $element[0]->attributes();
        foreach ($attributes as $name => $value) {
            if (strcasecmp($name, $attributeName) === 0) {
                return (string) $value;
            }
        }

        return;
    }

    /**
     * @param \SimpleXMLElement $element
     *
     * @return Media
     */
    public function createMedia(\SimpleXMLElement $element)
    {
        $media = new Media();
        $media->setUrl($this->searchAttributeValue($element, array('url', 'href', 'link')))
              ->setType($this->getAttributeValue($element, 'type'))
              ->setLength($this->getAttributeValue($element, 'length'));

        return $media;
    }

    /**
     * Looks for an attribute value under different possible names.
     *
     * @param \SimpleXMLElement $element
     * @param array             $names
     *
     * @return null|string|void
     */
    public function searchAttributeValue(\SimpleXMLElement $element, array $names)
    {
        foreach ($names as $name) {
            $value = $this->getAttributeValue($element, $name);
            if (!is_null($value)) {
                return (string) $value;
            }
        }

        return;
    }

    /**
     * Tells if the parser can handle the feed or not.
     *
     * @param \SimpleXMLElement $xmlBody
     *
     * @return bool
     */
    abstract public function canHandle(\SimpleXMLElement $xmlBody);

    /**
     * Performs the actual conversion into a FeedContent instance.
     *
     * @param \SimpleXMLElement $body
     * @param FeedInterface     $feed
     * @param array             $filters
     *
     * @return FeedInInterface
     */
    abstract protected function parseBody(\SimpleXMLElement $body, FeedInterface $feed, array $filters);

    /**
     * Handles enclosures if any.
     *
     * @param \SimpleXMLElement $element
     * @param ItemInInterface   $item
     *
     * @return $this
     */
    abstract protected function handleEnclosure(\SimpleXMLElement $element, ItemInInterface $item);
}
