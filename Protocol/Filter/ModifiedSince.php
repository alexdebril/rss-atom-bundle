<?php

/**
 * Rss/Atom Bundle for Symfony 2.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol\Filter;

use Debril\RssAtomBundle\Protocol\FilterInterface;
use Debril\RssAtomBundle\Protocol\Parser\Item;

/**
 * Class ModifiedSince.
 */
class ModifiedSince implements FilterInterface
{
    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @param \DateTime $date
     */
    public function __construct(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * The item is valid if it was last updated after the modified since date.
     *
     * @param Item $item
     *
     * @return bool
     */
    public function isValid(Item $item)
    {
        if ($item->getUpdated() instanceof \DateTime) {
            return $item->getUpdated() > $this->getDate();
        }

        return false;
    }
}
