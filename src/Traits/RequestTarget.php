<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

use Psr\Http\Message\RequestInterface;

trait RequestTarget
{

    private string $request_target = '';

    /**
     * @inheritDoc
     */
    public function getRequestTarget(): string
    {
        return $this->request_target;
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        if ($this->request_target === $requestTarget) {
            return $this;
        }

        $new = clone $this;
        $new->request_target = $requestTarget;

        return $new;
    }
}
