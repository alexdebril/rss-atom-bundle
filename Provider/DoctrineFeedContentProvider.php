<?php

/**
 * Feed aggregator for Symfony 2.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Provider;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Debril\RssAtomBundle\Exception\FeedNotFoundException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @codeCoverageIgnore
 */
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
     * Returns the name of the doctrine repository.
     *
     * @return string
     */
    public function getRepositoryName()
    {
        return $this->repositoryName;
    }

    /**
     * Sets the doctrine's repository name.
     *
     * @param string $repositoryName
     *
     * @return \Debril\RssAtomBundle\Provider\DoctrineFeedContentProvider
     */
    public function setRepositoryName($repositoryName)
    {
        $this->repositoryName = $repositoryName;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return \Debril\RssAtomBundle\Protocol\FeedOut
     *
     * @throws FeedNotFoundException
     */
    public function getFeedContent(array $options)
    {
        // fetch feed from data repository
        $feed = $this->getDoctrine()
                ->getManager()
                ->getRepository($this->getRepositoryName())
                ->findOneById($this->getIdFromOptions($options));

        // if the feed is an actual FeedOut instance, then return it
        if ($feed instanceof \Debril\RssAtomBundle\Protocol\FeedOut) {
            return $feed;
        }

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

    /**
     * @param array $options
     *
     * @return mixed
     */
    public function getIdFromOptions(array $options)
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setRequired('id');

        $options = $optionsResolver->resolve($options);

        return $options['id'];
    }
}
