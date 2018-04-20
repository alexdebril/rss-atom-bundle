<?php declare(strict_types=1);

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
use FeedIo\FeedInterface;

/**
 * Class MockProvider.
 */
class MockProvider implements FeedContentProviderInterface
{
    /**
     * @param array $options
     *
     * @return FeedInterface
     *
     * @throws FeedNotFoundException
     */
    public function getFeedContent(array $options) : FeedInterface
    {
        $feed = new Feed();

        $id = array_key_exists('id', $options) ? $options['id'] : null;

        if ($id === 'not-found') {
            throw new FeedNotFoundException();
        }

        $feed->setPublicId($id);

        $feed->setTitle('thank you for using RssAtomBundle');
        $feed->setDescription('this is the mock FeedContent');
        $feed->setLink('https://raw.github.com/alexdebril/rss-atom-bundle/');
        $feed->setLastModified(new \DateTime());

        return $this->addItem($feed);
    }

    /**
     * @param Feed $feed
     * @return Feed
     */
    protected function addItem(Feed $feed) : FeedInterface
    {
        $item = new Item();

        $item->setPublicId('1');
        $item->setLink('https://raw.github.com/alexdebril/rss-atom-bundle/somelink');
        $item->setTitle('This is an item');
        $item->setDescription('this stream was generated using the MockProvider class');
        $item->setLastModified(new \DateTime());

        $feed->add($item);

        return $feed;        
    }
}
