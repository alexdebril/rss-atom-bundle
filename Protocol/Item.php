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
use \Debril\RssAtomBundle\Protocol\Author;

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
     * RSS : description
     * ATOM : Content
     *
     * @var string
     */
    protected $description;

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
     *
     * @var \Debril\RssAtomBundle\Protocol\Author
     */
    protected $author;

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
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @param string $description
     * @return \Debril\RssAtomBundle\Protocol\Item
     */
    public function setDescription($description)
    {
        $this->description = $description;

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
     *
     * @return \Debril\RssAtomBundle\Protocol\Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     *
     * @param \Debril\RssAtomBundle\Protocol\Author $author
     * @return \Debril\RssAtomBundle\Protocol\Item
     */
    public function setAuthor(Author $author)
    {
        $this->author = $author;
        return $this;
    }

}

