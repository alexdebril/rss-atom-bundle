<?php

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 03/01/14
 * Time: 09:30
 */

namespace Debril\RssAtomBundle\Protocol\Filter;

class Limit implements \Debril\RssAtomBundle\Protocol\Filter
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
     * @param  \Debril\RssAtomBundle\Protocol\Parser\Item $item
     * @return boolean
     */
    public function isValid(\Debril\RssAtomBundle\Protocol\Parser\Item $item)
    {
        return ($this->limit > $this->count++);
    }

}
