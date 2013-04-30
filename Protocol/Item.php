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
namespace Debril\RssAtomBundle\Protocol;

use \DateTime;

class Item
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $summary;

    /**
     * @var DateTime
     */
    protected $updated;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $link;

    /**
     * @var string
     */
    protected $image;
    
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return \Debril\RssAtomBundle\Protocol\Item
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return \Debril\RssAtomBundle\Protocol\Item
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param unknown_type $summary
     * @return \Debril\RssAtomBundle\Protocol\Item
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param DateTime $updated
     * @return \Debril\RssAtomBundle\Protocol\Item
     */
    public function setUpdated(DateTime $updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param unknown_type $link
     * @return \Debril\RssAtomBundle\Protocol\Item
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param unknown_type $image
     * @return \Debril\RssAtomBundle\Protocol\Item
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
}