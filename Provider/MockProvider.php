<?php

/**
 * RssAtomBundle
 *
 * @package RssAtomBundle/Provider
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 *
 * creation date : 31 mars 2013
 *
 */

namespace Debril\RssAtomBundle\Provider;

use \Symfony\Component\OptionsResolver\Options;
use Debril\RssAtomBundle\Protocol\FeedContent;
use Debril\RssAtomBundle\Protocol\Item;
use Debril\RssAtomBundle\Protocol\Author;

class MockProvider implements FeedContentProvider
{

    /**
     *
     * @param \Symfony\Component\OptionsResolver\Options $options
     * @return \Debril\RssAtomBundle\Protocol\FeedContent
     */
    public function getFeedContentById(Options $options)
    {
        $content = new FeedContent;

        $contentId = $options->get('contentId');
        $content->setId($contentId);

        $content->setTitle('thank you for using RssAtomBundle');
        $content->setSubtitle('this is the mock FeedContent');
        $content->setLink('https://raw.github.com/alexdebril/rss-atom-bundle/');
        $content->setLastModified(new \DateTime);

        $item = new Item;

        $item->setId('1');
        $item->setLink('https://raw.github.com/alexdebril/rss-atom-bundle/somelink');
        $item->setTitle('This is an item');
        $item->setSummary('this stream was generated using the MockProvider class');
        $item->setUpdated(new \DateTime);

        $author = new Author;

        $author->setEmail('contact@example.com');
        $author->setName('Contributor');
        $author->setUri('http://example.com');
        $item->setAuthor($author);

        $content->addItem($item);

        return $content;
    }

}

