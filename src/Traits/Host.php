<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

use Psr\Http\Message\UriInterface;

trait Host
{

    private string $host = '';

    /**
     * @inheritDoc
     */
    public function getHost(): string
    {
        return strtolower($this->host);
    }

    /**
     * @inheritDoc
     */
    public function withHost(string $host): UriInterface
    {
        if ($this->host === $host) {
            return $this;
        }

        $new = clone $this;
        $new->host = $host;

        return $new;
    }
}
