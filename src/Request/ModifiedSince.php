<?php declare(strict_types=1);

namespace Debril\RssAtomBundle\Request;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ModifiedSince
{

    const HTTP_HEADER_NAME = 'If-Modified-Since';

    private $logger;

    private $requestStack;

    public function __construct(RequestStack $requestStack, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->requestStack = $requestStack;
    }

    /**
     * @return \DateTime
     */
    public function getValue(): \DateTime
    {
        return $this->getModifiedSince($this->requestStack->getCurrentRequest());
    }

    private function getModifiedSince(Request $request):\DateTime
    {
        if ($request->headers->has(self::HTTP_HEADER_NAME)) {
            try {
                $string = $request->headers->get(self::HTTP_HEADER_NAME);
                return new \DateTime($string);
            } catch (\TypeError|\Exception $e) {
                $this->logger->notice(sprintf('If-Modified-Since Header has a unexpected value, exception was %s', $e->getMessage()));
            }
        }

        return new \DateTime('@1');
    }

}
