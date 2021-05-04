<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class Request
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * Request constructor.
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }
}