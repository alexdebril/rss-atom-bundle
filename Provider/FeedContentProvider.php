<?php

/**
 * RssAtomBundle
 *
 * @package RssAtomBundle/Provider
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 *
 * creation date : 31 mars 2013
 *
 */

namespace Debril\RssAtomBundle\Provider;

use \Symfony\Component\OptionsResolver\Options;

interface FeedContentProvider
{

    /**
     *
     * @param \Symfony\Component\OptionsResolver $params
     * @throws \Debril\RssAtomBundle\Exception\FeedNotFoundException
     */
    public function getFeedContent(Options $options);
}

