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
use Symfony\Component\HttpFoundation\Request;

class MockProvider implements FeedProviderInterface
{

    /**
     * @param Request $request
     * @return FeedInterface
     * @throws FeedNotFoundException
     */
    public function getFeed(Request $request): FeedInterface
    {
        $id = $request->get('id');

        return $this->buildFeed($id);
    }

    /**
     * @param string $id
     * @return FeedInterface
     * @throws FeedNotFoundException
     */
    protected function buildFeed(string $id): FeedInterface
    {
        if ($id === 'not-found') {
            throw new FeedNotFoundException();
        }

        $feed = new Feed();

        $feed->setPublicId($id);
        $feed->setTitle('thank you for using RssAtomBundle');
        $feed->setDescription('this is the mock FeedContent');
        $feed->setLink('https://raw.github.com/alexdebril/rss-atom-bundle/');
        $feed->setLastModified(new \DateTime());

        return $this->addItem($feed);
    }

    /**
     * @param Feed $feed
     * @return FeedInterface
     * @throws \Exception
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
