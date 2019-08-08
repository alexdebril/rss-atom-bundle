<?php declare(strict_types=1);

namespace Debril\RssAtomBundle\Controller;

use Debril\RssAtomBundle\Request\ModifiedSince;
use Debril\RssAtomBundle\Provider\FeedContentProviderInterface;
use Debril\RssAtomBundle\Exception\FeedException\FeedNotFoundException;
use Debril\RssAtomBundle\Response\FeedBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class StreamController.
 */
class StreamController
{

    /**
     * @var FeedBuilder
     */
    private $feedBuilder;

    /**
     * @var ModifiedSince
     */
    private $modifiedSince;

    /**
     * @param FeedBuilder $feedBuilder
     * @param ModifiedSince $modifiedSince
     */
    public function __construct(FeedBuilder $feedBuilder, ModifiedSince $modifiedSince)
    {
        $this->feedBuilder = $feedBuilder;
        $this->modifiedSince = $modifiedSince;
    }

    /**
     * @param Request $request
     * @param FeedContentProviderInterface $provider
     * @return Response
     * @throws \Exception
     */
    public function indexAction(Request $request, FeedContentProviderInterface $provider) : Response
    {
        $options = $request->attributes->get('_route_params');
        $options['Since'] = $this->modifiedSince->getValue();

        try {
            return $this->feedBuilder->getResponse(
                $request->get('format', 'rss'),
                $provider->getFeedContent($options)
            );
        } catch (FeedNotFoundException $e) {
            throw new NotFoundHttpException('feed not found');
        }
    }

}
