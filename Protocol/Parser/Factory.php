<?php

/**
 * Rss/Atom Bundle for Symfony 2.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol\Parser;

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
     * @return \Debril\RssAtomBundle\Protocol\FeedInInterface
     *
     * @throws Exception
     */
    public function newFeed()
    {
        $newFeed = new $this->feedClass();
        if (!$newFeed instanceof \Debril\RssAtomBundle\Protocol\FeedInInterface) {
            throw new \Exception("{$this->feedClass} does not implement FeedInInterface interface");
        }

        return $newFeed;
    }

    /**
     * @return \Debril\RssAtomBundle\Protocol\ItemInInterface
     *
     * @throws Exception
     */
    public function newItem()
    {
        $newItem = new $this->itemClass();
        if (!$newItem instanceof \Debril\RssAtomBundle\Protocol\ItemInInterface) {
            throw new \Exception("{$this->itemClass} does not implement ItemInInterface interface");
        }

        return $newItem;
    }

    /**
     * @param string $feedClass
     *
     * @return \Debril\RssAtomBundle\Protocol\Parser\Factory
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
     * @return \Debril\RssAtomBundle\Protocol\Parser\Factory
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
