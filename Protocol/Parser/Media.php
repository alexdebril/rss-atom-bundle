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

namespace Debril\RssAtomBundle\Protocol\Parser;


class Media
{
    
    /**
     * @var string
     */
    protected $type;
    
    /**
     * @var string
     */
    protected $url;
    
    /**
     * @var int
     */
    protected $lenght;
    
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * @param string $type
     * @return $this
     */   
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }
    
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * @param string $url
     * @return $this
     */   
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }
    
    /**
     * @return string
     */
    public function getLenght()
    {
        return $this->lenght;
    }
    
    /**
     * @param string $lenght
     * @return $this
     */   
    public function setLenght($lenght)
    {
        $this->lenght = intval($lenght);
    
        return $this;
    }
}
