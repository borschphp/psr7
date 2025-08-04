<?php declare(strict_types=1);

namespace Borsch\Http\Factory;

use Borsch\Http\ServerRequest;
use Psr\Http\Message\{ServerRequestFactoryInterface,
    ServerRequestInterface,
    StreamFactoryInterface,
    UriFactoryInterface
};
use function is_string;

readonly class ServerRequestFactory implements ServerRequestFactoryInterface
{

    public function __construct(
        private UriFactoryInterface $uri_factory = new UriFactory(),
        private StreamFactoryInterface $stream_factory = new StreamFactory(),
    ) {}

    /**
     * @inheritDoc
     *
     * @param array<string, string> $serverParams
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        if (is_string($uri)) {
            $uri = $this->uri_factory->createUri($uri);
        }

        return new ServerRequest(
            $method,
            $uri,
            $this->stream_factory->createStream(''),
            server_params: $serverParams
        );
    }
}
