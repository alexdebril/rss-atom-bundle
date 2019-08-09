<?php declare(strict_types=1);

namespace Debril\RssAtomBundle\Request;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ModifiedSince
{

    const HTTP_HEADER_NAME = 'If-Modified-Since';

    private $value;

    public function __construct(RequestStack $requestStack)
    {
        $this->value = $this->getModifiedSince($requestStack->getCurrentRequest());
    }

    /**
     * @return \DateTime
     */
    public function getValue(): \DateTime
    {
        return $this->value;
    }

    private function getModifiedSince(Request $request):\DateTime
    {
        if ($request->headers->has(self::HTTP_HEADER_NAME)) {
            $string = $request->headers->get(self::HTTP_HEADER_NAME);
            return \DateTime::createFromFormat(\DateTime::RSS, $string);
        }

        return new \DateTime('@1');
    }
}
