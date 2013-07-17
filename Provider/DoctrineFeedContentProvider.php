<?php

/**
 * Feed aggregator for Symfony 2
 *
 * @package FeedAggregatorBundle/Provider
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 *
 */

namespace Debril\RssAtomBundle\Provider;

use \Symfony\Component\OptionsResolver\Options;
use \Doctrine\Bundle\DoctrineBundle\Registry;
use \Debril\RssAtomBundle\Provider\FeedContentProvider;
use \Debril\RssAtomBundle\Exception\FeedNotFoundException;

class DoctrineFeedContentProvider implements FeedContentProvider
{

    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected $doctrine;

    /**
     * @var string
     */
    protected $repositoryName;

    /**
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Returns the name of the doctrine repository
     * @return string
     */
    public function getRepositoryName()
    {
        return $this->repositoryName;
    }

    /**
     * Sets the doctrine's repository name
     * @param string $repositoryName
     * @return \Debril\RssAtomBundle\Provider\DoctrineFeedContentProvider
     */
    public function setRepositoryName($repositoryName)
    {
        $this->repositoryName = $repositoryName;

        return $this;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\Options $options
     * @return \Debril\FeedAggregatorBundle\Provider\Feed
     * @throws FeedNotFoundException
     */
    public function getFeedContent(Options $options)
    {
        // fetch feed from data repository
        $feed = $this->getDoctrine()
                ->getManager()
                ->getRepository($this->getRepositoryName())
                ->getFeed($options->get('id'));

        // if the feed is an actual FeedOut instance, then return it
        if ($feed instanceof \Debril\RssAtomBundle\Protocol\FeedOut)
            return $feed;

        // $feed is null, which means no Feed was found with this id.
        throw new FeedNotFoundException();
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

}

