<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

use Psr\Http\Message\UriInterface;

trait Path
{

    private string $path = '';

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        if (str_starts_with($this->path, '//')) {
            return '/'.ltrim($this->path, '/');
        }

        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function withPath(string $path): UriInterface
    {
        if ($this->path === $path) {
            return $this;
        }

        $new = clone $this;
        $new->path = $path;

        return $new;
    }
}
