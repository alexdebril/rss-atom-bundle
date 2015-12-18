<?php

/**
 * Rss/Atom Bundle for Symfony 2.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol\Parser;

use Debril\RssAtomBundle\Protocol\FeedInInterface;
use Debril\RssAtomBundle\Protocol\FeedInterface;
use Debril\RssAtomBundle\Protocol\ItemInInterface;
use Debril\RssAtomBundle\Protocol\Parser;
use SimpleXMLElement;

/**
 * Class RdfParser.
 */
class RdfParser extends Parser
{
    protected $mandatoryFields = array(
        'channel',
    );

    /**
     *
     */
    public function __construct()
    {
        $this->setdateFormats(array(\DateTime::W3C, 'Y-m-d'));
    }

    /**
     * @param SimpleXMLElement $xmlBody
     *
     * @return bool
     */
    public function canHandle(SimpleXMLElement $xmlBody)
    {
        return 'rdf' === strtolower($xmlBody->getName());
    }

    /**
     * @param SimpleXMLElement $xmlBody
     * @param FeedInterface    $feed
     * @param array            $filters
     *
     * @return FeedInInterface
     */
    protected function parseBody(SimpleXMLElement $xmlBody, FeedInterface $feed, array $filters)
    {
        $feed->setPublicId($xmlBody->channel->link);
        $feed->setLink($xmlBody->channel->link);
        $feed->setTitle($xmlBody->channel->title);
        $feed->setDescription($xmlBody->channel->description);

        if (isset($xmlBody->channel->date)) {
            $date = $xmlBody->channel->children('dc', true);
            $updated = static::convertToDateTime($date[0], $this->guessDateFormat($date[0]));
            $feed->setLastModified($updated);
        }

        $format = null;
        foreach ($xmlBody->item as $xmlElement) {
            $item = $this->newItem();
            $date = $xmlElement->children('dc', true);
            $format = !is_null($format) ? $format : $this->guessDateFormat($date[0]);

            $item->setTitle($xmlElement->title)
                    ->setDescription($xmlElement->description)
                    ->setUpdated(static::convertToDateTime($date[0], $format))
                    ->setLink($xmlElement->link);

            $this->addValidItem($feed, $item, $filters);
        }

        return $feed;
    }

    /**
     * RDF format doesn't support enclosures.
     *
     * @param SimpleXMLElement $element
     * @param ItemInInterface  $item
     *
     * @return $this
     */
    protected function handleEnclosure(SimpleXMLElement $element, ItemInInterface $item)
    {
        return $this;
    }
}
