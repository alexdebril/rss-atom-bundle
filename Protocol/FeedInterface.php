<?php

/**
 * Rss/Atom Bundle for Symfony.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2014, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Protocol;

/**
 * Transitional interface which deprecates FeedInInterface and FeedOutInterface
 * Interface FeedInterface.
 */
interface FeedInterface extends FeedInInterface, FeedOutInterface
{
}
