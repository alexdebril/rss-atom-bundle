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
 * @deprecated since version 1.2.0
 */
interface AtomItem
{
    /**
     * HTML type
     */

    const HTML = 'html';

    /**
     * XHTML type
     */
    const XHTML = 'xhtml';

    /**
     * text Type
     */
    const TEXT = 'text';

    /**
     * atom only
     * @return string
     */
    public function getSummary();

    /**
     *
     * @return string
     */
    public function getContentType();
}

