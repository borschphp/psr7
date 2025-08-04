<?php

namespace Borsch\Http\Factory;

use Borsch\Http\Request;
use Psr\Http\Message\{RequestFactoryInterface, RequestInterface, UriFactoryInterface};
use function is_string;

readonly class RequestFactory implements RequestFactoryInterface
{

    public function __construct(
        private UriFactoryInterface $uri_factory = new UriFactory()
    ) {}

    /**
     * @inheritDoc
     */
    public function createRequest(string $method, $uri): RequestInterface
    {
        if (is_string($uri)) {
            $uri = $this->uri_factory->createUri($uri);
        }

        return new Request($method, $uri);
    }
}