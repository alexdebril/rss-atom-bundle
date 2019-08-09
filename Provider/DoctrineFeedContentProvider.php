<?php declare(strict_types=1);

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
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @deprecated since 4.3, will be dropped in 5.0
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
     * DoctrineFeedContentProvider constructor.
     * @param Registry $doctrine
     * @param LoggerInterface $logger
     */
    public function __construct(Registry $doctrine, LoggerInterface $logger)
    {
        $logger->info('The \\Debril\\RssAtomBundle\\Provider\\DoctrineFeedContentProvider is deprecated since rss-atom-bundle 4.3, will be removed in 5.0');
        $this->doctrine = $doctrine;
    }

    /**
     * Returns the name of the doctrine repository.
     *
     * @return string
     */
    public function getRepositoryName() : string
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
    public function setRepositoryName(string $repositoryName) : self
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
    public function getFeedContent(array $options) : FeedInterface
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
     * @return string
     */
    public function getIdFromOptions(array $options) : string
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setRequired('id');

        $options = $optionsResolver->resolve($options);

        return $options['id'];
    }

    /**
     * @param Request $request
     * @return FeedInterface
     * @throws FeedNotFoundException
     */
    public function getFeed(Request $request): FeedInterface
    {
        return $request->get('id');
    }
}
