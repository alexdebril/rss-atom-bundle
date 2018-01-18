<?php

# src/Feed/provider.php

namespace App\Feed;

// this part assumes that you published "Post" entities
use App\Entity\Post;
use App\Repository\PostRepository;

// All you really need to create a feed
use Debril\RssAtomBundle\Provider\FeedContentProviderInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;
use FeedIo\Feed;
use FeedIo\Feed\Node\Category;
use FeedIo\FeedInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Router;

class Provider implements FeedContentProviderInterface
{

    protected $logger;

    protected $registry;

    protected $router;

    /**
     * Provider constructor.
     * @param LoggerInterface $logger
     * @param Registry $registry
     * @param Router $router
     */
    public function __construct(LoggerInterface $logger, Registry $registry, Router $router)
    {
        $this->logger = $logger;
        $this->registry = $registry;
        $this->router = $router;
    }

    /**
     * @param array $options
     * @return FeedInterface
     */
    public function getFeedContent(array $options) : FeedInterface
    {
        $feed = new Feed();
        $feed->setTitle('Feed Title')
            ->setLink('Feed URL')
            ->setDescription('Feed description')
            ->setPublicId('Feed ID');

        $lastPostPublicationDate = null;

        $posts = $this->getPosts();

        /**
         * @var \App\Entity\Post $post
         */
        foreach ($posts as $post) {
            $lastPostPublicationDate = is_null($lastPostPublicationDate) ? $post->getPublicationDate():$lastPostPublicationDate;

            $item = new Feed\Item();
            $item->setTitle($post->getTitle());

            $category = new Category();
            $category->setLabel($post->getCategory());
            $item->addCategory($category);

            $item->setLastModified($post->getPublicationDate());

            // ... and all the stuff about content, public id, etc ...

            $feed->add($item);
        }

        // if the publication date is still empty, set it the current Date
        $lastPostPublicationDate = is_null($lastPostPublicationDate) ? new \DateTime():$lastPostPublicationDate;
        $feed->setLastModified($lastPostPublicationDate);

        return $feed;
    }

    /**
     * You'll need to code this
     * @return array
     */
    protected function getPosts()
    {
        return [];
    }

}
