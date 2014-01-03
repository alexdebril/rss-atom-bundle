<?php

/**
 * Rss/Atom Bundle for Symfony 2
 *
 * @package RssAtomBundle\Protocol
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 *
 */

namespace Debril\RssAtomBundle\Protocol\Filter;

class ModifiedSince implements \Debril\RssAtomBundle\Protocol\Filter
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
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * The item is valid if it was last updated after the modified since date
     *
     * @param \Debril\RssAtomBundle\Protocol\ItemIn $item
     * @return boolean
     */
    public function isValid(\Debril\RssAtomBundle\Protocol\ItemIn $item)
    {
        if ($item->getUpdated() instanceof \DateTime)
        {
            return $item->getUpdated() > $this->getDate();
        }

        return false;
    }

}
