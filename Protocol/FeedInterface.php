<?php

/**
 * Rss/Atom Bundle for Symfony 2
 *
 * @package RssAtomBundle\Protocol
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2014, Alexandre Debril
 *
 */

namespace Debril\RssAtomBundle\Protocol;

/**
 * Transitional interface which deprecates FeedIn and FeedOut
 * Interface FeedInterface
 * @package Debril\RssAtomBundle\Protocol
 */
interface FeedInterface extends FeedIn, FeedOut { }
