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

namespace Debril\RssAtomBundle\Protocol\Parser;

use Debril\RssAtomBundle\Protocol\Parser;
use Debril\RssAtomBundle\Protocol\FeedIn;
use \SimpleXMLElement;

class AtomParser extends Parser
{

    protected $mandatoryFields = array(
        'id',
        'updated',
        'title',
        'link',
        'entry',
    );

    /**
     *
     */
    public function __construct()
    {
        $this->setdateFormats(array(\DateTime::RFC3339));
    }

    /**
     * @param SimpleXMLElement $xmlBody
     * @return boolean
     */
    public function canHandle(SimpleXMLElement $xmlBody)
    {
        return 'feed' === $xmlBody->getName();
    }

    /**
     *
     * @param SimpleXMLElement $xmlBody
     * @param Debril\RssAtomBundle\Protocol\FeedIn $feed
     * @param \DateTime $modifiedSince
     * @return \\Debril\RssAtomBundle\Protocol\FeedIn
     */
    protected function parseBody(SimpleXMLElement $xmlBody, FeedIn $feed, \DateTime $modifiedSince)
    {
        $feed->setId($xmlBody->id);

        $feed->setLink(current($xmlBody->link[0]['href']));
        $feed->setTitle($xmlBody->title);
        $feed->setDescription($xmlBody->subtitle);

        $format = $this->guessDateFormat($xmlBody->updated);
        $updated = self::convertToDateTime($xmlBody->updated, $format);
        $feed->setLastModified($updated);

        foreach ($xmlBody->entry as $xmlElement)
        {
            $item = $this->newItem();
            $item->setTitle($xmlElement->title)
                    ->setId($xmlElement->id)
                    ->setSummary($xmlElement->summary)
                    ->setContentType($xmlElement->content[0]['type'])
                    ->setDescription($this->parseContent($xmlElement->content))
                    ->setUpdated(self::convertToDateTime($xmlElement->updated, $format))
                    ->setLink($xmlElement->link[0]['href']);

            if ($xmlElement->author)
            {
                $item->setAuthor($xmlElement->author->name);
            }

            $this->addAcceptableItem($feed, $item, $modifiedSince);
        }

        return $feed;
    }

    protected function parseContent(SimpleXMLElement $content)
    {
        if (0 < $content->children()->count())
        {
            $out = '';
            foreach ($content->children() as $child)
            {
                $out .= $child->asXML();
            }
            return $out;
        }

        return $content;
    }

}

