<?php

namespace Borsch\Http;

use Borsch\Http\Traits\{Fragment, Host, Path, Port, Query, UserInfo, Scheme};
use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use function parse_url;

class Uri implements UriInterface
{

    use Fragment, Host, Path, Port, Query, Scheme, UserInfo;

    private const SCHEMES_PORTS = [
        'http' => 80,
        'https' => 443,
        'ftp' => 21,
        'ws' => 80,
        'wss' => 443,
    ];

    public function __construct(string $uri)
    {
        $parts = parse_url($uri);
        if ($parts === false) {
            throw new InvalidArgumentException('Invalid URI: ' . $uri);
        }

        $this->scheme = $parts['scheme'] ?? '';
        $this->host = $parts['host'] ?? '';

        $this->port = $parts['port'] ?? null;
        if (isset(self::SCHEMES_PORTS[strtolower($this->scheme)]) &&
            self::SCHEMES_PORTS[strtolower($this->scheme)] === ($parts['port'] ?? null)) {
            $this->port = null;
        }

        $this->path = str_replace(' ', '%20', $parts['path'] ?? '');
        $this->query = $parts['query'] ?? '';
        $this->fragment = $parts['fragment'] ?? '';
        $this->user = $parts['user'] ?? null;
        $this->password = $parts['pass'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        $uri = $this->getScheme() . '://';

        $authority = $this->getAuthority();
        if ($authority !== '') {
            $uri .= $authority;
        }

        $uri .= $this->path;

        $query = $this->getQuery();
        if ($query !== '') {
            $uri .= '?' . $query;
        }

        $fragment = $this->getFragment();
        if ($fragment !== '') {
            $uri .= '#' . $fragment;
        }

        return $uri;
    }

    /**
     * @inheritDoc
     */
    public function getAuthority(): string
    {
        $authority = '';

        $user_info = $this->getUserInfo();
        if ($user_info !== '') {
            $authority .= $user_info . '@';
        }

        $authority .= $this->getHost();

        $port = $this->getPort();
        if ($port !== null && $port !== self::SCHEMES_PORTS[$this->getScheme()]) {
            $authority .= ':' . $port;
        }

        return $authority;
    }
}
