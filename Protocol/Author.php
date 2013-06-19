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

class Author
{

    /**
     *
     * @var string
     */
    protected $uri;

    /**
     *
     * @var string
     */
    protected $email;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     *
     * @param string $uri
     * @return \Debril\RssAtomBundle\Protocol\Author
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     *
     * @param string $email
     * @return \Debril\RssAtomBundle\Protocol\Author
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param string $name
     * @return \Debril\RssAtomBundle\Protocol\Author
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

}
