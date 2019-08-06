<?php declare(strict_types=1);

namespace Debril\RssAtomBundle\Controller;

use Debril\RssAtomBundle\Request\ModifiedSince;
use Debril\RssAtomBundle\Response\HeadersBuilder;
use Debril\RssAtomBundle\Provider\FeedContentProviderInterface;
use Debril\RssAtomBundle\Exception\FeedException\FeedNotFoundException;
use FeedIo\FeedIo;
use FeedIo\FeedInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class StreamController.
 */
class StreamController
{

    /**
     * @var \DateTime
     */
    protected $since;

    /**
     * @var HeadersBuilder
     */
    private $headersBuilder;

    /**
     * @var ModifiedSince
     */
    private $modifiedSince;

    /**
     * @var bool
     */
    private $forceRefresh;

    /**
     * StreamController constructor.
     * @param HeadersBuilder $headersBuilder
     * @param ModifiedSince $modifiedSince
     * @param bool $forceRefresh
     */
    public function __construct(HeadersBuilder $headersBuilder, ModifiedSince $modifiedSince, bool $forceRefresh = false)
    {
        $this->headersBuilder = $headersBuilder;
        $this->modifiedSince = $modifiedSince;
        $this->forceRefresh = $forceRefresh;
    }

    /**
     * @param Request $request
     * @param FeedContentProviderInterface $provider
     * @param FeedIo $feedIo
     * @return Response
     * @throws \Exception
     */
    public function indexAction(Request $request, FeedContentProviderInterface $provider, FeedIo $feedIo) : Response
    {
        $options = $request->attributes->get('_route_params');
        $options['Since'] = $this->modifiedSince->getValue();

        $feed = $this->getContent($options, $provider);
        $format = $request->get('format', 'rss');

        if ($this->forceRefresh || $feed->getLastModified() > $this->modifiedSince->getValue()) {
            $response = new Response($feedIo->format($feed, $format));
            $this->headersBuilder->setResponseHeaders($response, $format, $feed->getLastModified());

        } else {
            $response = new Response();
            $response->setNotModified();
        }

        return $response;
    }

    /**
     * Get the Stream's content using a FeedContentProviderInterface
     * The FeedContentProviderInterface instance is provided as a service
     * default : debril.provider.service.
     *
     * @param array  $options
     * @param FeedContentProviderInterface $provider
     *
     * @return FeedInterface
     *
     * @throws \Exception
     */
    protected function getContent(array $options, FeedContentProviderInterface $provider) : FeedInterface
    {
        try {
            return $provider->getFeedContent($options);
        } catch (FeedNotFoundException $e) {
            throw new NotFoundHttpException('feed not found');
        }
    }

}
