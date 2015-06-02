<?php

/**
 * RssAtomBundle.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 *
 * creation date : 31 mars 2013
 */
namespace Debril\RssAtomBundle\Provider;

interface FeedContentProviderInterface
{
    /**
     * @param array $options
     *
     * @throws \Debril\RssAtomBundle\Exception\FeedException\FeedNotFoundException
     *
     * @return \Debril\RssAtomBundle\Protocol\FeedOutInterface
     */
    public function getFeedContent(array $options);
}
