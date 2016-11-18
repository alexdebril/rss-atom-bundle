<?php

/**
 * Feed aggregator for Symfony.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
namespace Debril\RssAtomBundle\Provider;

use FeedIo\FeedInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Debril\RssAtomBundle\Exception\FeedException\FeedNotFoundException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @codeCoverageIgnore
 */
class DoctrineFeedContentProvider implements FeedContentProviderInterface
{
    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @var string
     */
    protected $repositoryName;

    /**
     * @param Registry $doctrine
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
     * @return DoctrineFeedContentProvider
     */
    public function setRepositoryName($repositoryName)
    {
        $this->repositoryName = $repositoryName;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return FeedInterface
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

        // if the feed is an actual FeedInterface instance, then return it
        if ($feed instanceof FeedInterface) {
            return $feed;
        }

        // $feed is null, which means no Feed was found with this id.
        throw new FeedNotFoundException();
    }

    /**
     * @return Registry
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
