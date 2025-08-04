<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

trait Uri
{

    private UriInterface $uri;

    /**
     * @inheritDoc
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        if ($this->uri === $uri) {
            return $this;
        }

        $new = clone $this;
        $new->uri = $uri;
        $new->request_target = $uri->getPath();

        if ($preserveHost && $new->hasHeader('Host')) {
            return $new;
        }

        if ($uri->getHost() !== '') {
            $new = $new->withHeader('Host', $new->uri->getHost());
        }

        return $new;
    }
}
