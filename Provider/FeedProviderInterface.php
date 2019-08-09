<?php declare(strict_types=1);

namespace Debril\RssAtomBundle\Provider;

use Debril\RssAtomBundle\Exception\FeedException\FeedNotFoundException;
use FeedIo\FeedInterface;
use Symfony\Component\HttpFoundation\Request;

interface FeedProviderInterface
{

    /**
     * @param Request $request
     * @return FeedInterface
     * @throws FeedNotFoundException
     */
    public function getFeed(Request $request): FeedInterface;

}
