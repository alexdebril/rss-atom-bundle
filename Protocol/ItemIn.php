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

/**
 * interface used to represent incoming items
 */
interface ItemIn
{

    /**
     *
     * @param string $title
     */
    public function setTitle($title);

    /**
     *
     * @param mixed $id
     */
    public function setId($id);

    /**
     *
     * @param string $description
     */
    public function setDescription($description);

    /**
     *
     * @param string $summary
     */
    public function setSummary($summary);

    /**
     *
     * @param \DateTime $updated
     */
    public function setUpdated(\DateTime $updated);

    /**
     *
     * @param string $link
     */
    public function setLink($link);

    /**
     *
     * @param string $author
     */
    public function setAuthor($author);

    /**
     *
     * @param string $comment
     */
    public function setComment($comment);

    /**
     *
     * @param string $type
     */
    public function setContentType($type);
}

