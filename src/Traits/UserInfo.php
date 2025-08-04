<?php

namespace Borsch\Http\Traits;

use Psr\Http\Message\UriInterface;
use function sprintf;

trait UserInfo
{

    private ?string $user = null;
    private ?string $password = null;

    /**
     * @inheritDoc
     */
    public function getUserInfo(): string
    {
        if ($this->user === null) {
            return '';
        }

        if ($this->password === null) {
            return $this->user;
        }

        return sprintf('%s:%s', $this->user, $this->password);
    }

    /**
     * @inheritDoc
     */
    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        if ($this->user === $user && $this->password === $password) {
            return $this;
        }

        $new = clone $this;
        $new->user = $this->encodeUserInfoPart($user);
        $new->password = $password == null ? null : $this->encodeUserInfoPart($password);

        return $new;
    }

    private function encodeUserInfoPart(string $user_info): string
    {
        return (string)preg_replace_callback(
            '/(?:%[0-9A-Fa-f]{2})|[^a-zA-Z0-9_\-\.~]/',
            function ($match) {
                // If already percent-encoded, preserve it
                if (substr($match[0], 0, 1) === '%') {
                    return $match[0];
                }

                // Otherwise encode the character
                return rawurlencode($match[0]);
            },
            $user_info
        );
    }
}
