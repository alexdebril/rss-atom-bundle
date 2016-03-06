<?php

/**
 * Rss/Atom Bundle for Symfony.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol\Parser;

use Debril\RssAtomBundle\Protocol\FeedInInterface;
use Debril\RssAtomBundle\Protocol\ItemInInterface;

/**
 * Class Factory.
 */
class Factory
{
    /**
     * @var string
     */
    protected $feedClass = 'Debril\RssAtomBundle\Protocol\Parser\FeedContent';

    /**
     * @var string
     */
    protected $itemClass = 'Debril\RssAtomBundle\Protocol\Parser\Item';

    /**
     * @return FeedInInterface
     *
     * @throws \Exception
     */
    public function newFeed()
    {
        $newFeed = new $this->feedClass();
        if (!$newFeed instanceof FeedInInterface) {
            throw new \Exception("{$this->feedClass} does not implement FeedInInterface interface");
        }

        return $newFeed;
    }

    /**
     * @return ItemInInterface
     *
     * @throws \Exception
     */
    public function newItem()
    {
        $newItem = new $this->itemClass();
        if (!$newItem instanceof ItemInInterface) {
            throw new \Exception("{$this->itemClass} does not implement ItemInInterface interface");
        }

        return $newItem;
    }

    /**
     * @param string $feedClass
     *
     * @return Factory
     *
     * @throws \Exception
     */
    public function setFeedClass($feedClass)
    {
        if (!class_exists($feedClass)) {
            throw new \Exception("{$feedClass} does not exist");
        }

        $this->feedClass = $feedClass;

        return $this;
    }

    /**
     * @param string $itemClass
     *
     * @return Factory
     *
     * @throws \Exception
     */
    public function setItemClass($itemClass)
    {
        if (!class_exists($itemClass)) {
            throw new \Exception("{$itemClass} does not exist");
        }

        $this->itemClass = $itemClass;

        return $this;
    }
}
