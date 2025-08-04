<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

use Psr\Http\Message\UriInterface;

trait Query
{

    private string $query;

    /**
     * @inheritDoc
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @inheritDoc
     */
    public function withQuery(string $query): UriInterface
    {
        $query = ltrim($query, '?');
        if ($this->query === $query) {
            return $this;
        }

        $new = clone $this;
        $new->query = $query;

        return $new;
    }
}
