<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

use Psr\Http\Message\UriInterface;

trait Fragment
{

    private string $fragment = '';

    /**
     * @inheritDoc
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * @inheritDoc
     */
    public function withFragment(string $fragment): UriInterface
    {
        $fragment = ltrim($fragment, '#');
        if ($this->fragment === $fragment) {
            return $this;
        }

        $new = clone $this;
        $new->fragment = $fragment;

        return $new;
    }
}
