<?php

/**
 * Rss/Atom Bundle for Symfony 2.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol\Filter;

/**
 * Class Limit.
 */
class Limit implements \Debril\RssAtomBundle\Protocol\FilterInterface
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
     * @param $limit
     */
    public function __construct($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @param \Debril\RssAtomBundle\Protocol\Parser\Item $item
     *
     * @return bool
     */
    public function isValid(\Debril\RssAtomBundle\Protocol\Parser\Item $item)
    {
        return ($this->limit > $this->count++);
    }
}
