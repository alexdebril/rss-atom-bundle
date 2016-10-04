<?php

/**
 * Rss/Atom Bundle for Symfony.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol\Filter;

use Debril\RssAtomBundle\Protocol\FilterInterface;
use Debril\RssAtomBundle\Protocol\Parser\Item;
use Debril\RssAtomBundle\Protocol\ItemOutInterface;

/**
 * Class Limit.
 * @deprecated removed in version 3.0
 */
class Limit implements FilterInterface
{
    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @param int $limit
     * @deprecated removed in version 3.0
     */
    public function __construct($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @param Item $item
     *
     * @return bool
     * @deprecated removed in version 3.0
     */
    public function isValid(ItemOutInterface $item)
    {
        return ($this->limit > $this->count++);
    }
}
