<?php

/**
 * RssAtomBundle.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 *
 * creation date : 31 mars 2013
 */
namespace Debril\RssAtomBundle\Provider;

use FeedIo\Feed;
use FeedIo\Feed\Item;
use Debril\RssAtomBundle\Exception\FeedException\FeedNotFoundException;

/**
 * Class MockProvider.
 */
class MockProvider implements FeedContentProviderInterface
{
    /**
     * @param array $options
     *
     * @return Feed
     *
     * @throws FeedNotFoundException
     */
    public function getFeedContent(array $options)
    {
        $content = new Feed();

        $id = array_key_exists('id', $options) ? $options['id'] : null;

        if ($id === 'not-found') {
            throw new FeedNotFoundException();
        }

        $content->setPublicId($id);

        $content->setTitle('thank you for using RssAtomBundle');
        $content->setDescription('this is the mock FeedContent');
        $content->setLink('https://raw.github.com/alexdebril/rss-atom-bundle/');
        $content->setLastModified(new \DateTime());

        $item = new Item();

        $item->setPublicId('1');
        $item->setLink('https://raw.github.com/alexdebril/rss-atom-bundle/somelink');
        $item->setTitle('This is an item');
        $item->setDescription('this stream was generated using the MockProvider class');
        $item->setLastModified(new \DateTime());

        $content->add($item);

        return $content;
    }
}
