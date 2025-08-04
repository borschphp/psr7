<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

use Psr\Http\Message\ServerRequestInterface;

trait CookieParams
{

    /** @var array<string, string> $cookie_params */
    private array $cookie_params = [];

    /**
     * @inheritDoc
     *
     * @return array<string, string>
     */
    public function getCookieParams(): array
    {
        return $this->cookie_params;
    }

    /**
     * @inheritDoc
     *
     * @param array<string, string> $cookies
     */
    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        $new = clone $this;
        $new->cookie_params = $cookies;

        return $new;
    }

}
