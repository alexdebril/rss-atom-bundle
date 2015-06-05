<?php

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25/04/14
 * Time: 23:44.
 */
namespace Debril\RssAtomBundle\Tests\Protocol;

use Debril\RssAtomBundle\Protocol\Parser\FeedContent;
use Debril\RssAtomBundle\Protocol\Parser\Item;
use Debril\RssAtomBundle\Protocol\Parser\Media;

class FormatterAbstract extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FeedContent
     */
    protected $feed;

    protected function setUp()
    {
        $this->feed = new FeedContent();

        $this->feed->setPublicId('feed id');
        $this->feed->setLink('http://example.com');
        $this->feed->setTitle('feed title');
        $this->feed->setDescription('feed subtitle');
        $this->feed->setLastModified(new \DateTime());

        $item = new Item();
        $item->setPublicId('item id');
        $item->setLink('http://example.com/1');
        $item->setSummary('lorem ipsum');
        $item->setTitle('title 1');
        $item->setUpdated(new \DateTime());
        $item->setComment('http://linktothecomments.com');
        $item->setAuthor('Contributor');

        $media = new Media();
        $media->setUrl('http://media');
        $media->setUrl('image/jpeg');

        $item->addMedia($media);

        $this->feed->addItem($item);
    }

    /**
     * @param $object
     */
    protected function _testSetEntries($object)
    {
        $element = $object->getRootElement();

        $object->setEntries($element, $this->feed);

        foreach ($element->childNodes as $entry) {
            $this->assertInstanceOf("\DomNode", $entry);
        }
    }
}
