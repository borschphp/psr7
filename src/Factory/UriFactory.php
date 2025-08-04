<?php declare(strict_types=1);

namespace Borsch\Http\Factory;

use Borsch\Http\Uri;
use Psr\Http\Message\{UriFactoryInterface, UriInterface};

class UriFactory implements UriFactoryInterface
{

    /**
     * @inheritDoc
     */
    public function createUri(string $uri = ''): UriInterface
    {
        return new Uri($uri);
    }
}
