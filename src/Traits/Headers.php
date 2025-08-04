<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;
use Psr\Http\Message\MessageInterface;
use function implode, strcasecmp;

trait Headers
{

    /** @var ArrayCollection<string, string[]> */
    protected ArrayCollection $headers;

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->headers->toArray();
    }

    /**
     * @inheritDoc
     */
    public function hasHeader(string $name): bool
    {
        foreach ($this->headers->getKeys() as $key) {
            if (strcasecmp($name, $key) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getHeader(string $name): array
    {
        foreach ($this->headers as $key => $header) {
            if (strcasecmp($name, $key) === 0) {
                return $header;
            }
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine(string $name): string
    {
        $headers = $this->getHeader($name);
        if (empty($headers)) {
            return '';
        }

        return implode(', ', $headers);
    }

    /**
     * @inheritDoc
     */
    public function withHeader(string $name, $value): MessageInterface
    {
        if ($this->hasHeader($name)) {
            foreach ($this->headers->getKeys() as $key) {
                if (strcasecmp($name, $key) === 0) {
                    $this->headers->remove($key);
                    break;
                }
            }
        }

        $this->validateHeader($name, $value);

        $new = clone $this;
        $new->headers->set($name, array_values((array)$value));

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        if (!$this->hasHeader($name)) {
            return $this->withHeader($name, $value);
        }

        foreach ($this->headers->getKeys() as $key) {
            if (strcasecmp($name, $key) === 0) {
                $name = $key;
                break;
            }
        }

        $headers = array_merge($this->getHeader($name), array_values((array)$value));

        return $this->withHeader($name, $headers);
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader(string $name): MessageInterface
    {
        if (!$this->hasHeader($name)) {
            return $this;
        }

        foreach ($this->headers->getKeys() as $key) {
            if (strcasecmp($name, $key) === 0) {
                $name = $key;
                break;
            }
        }

        $new = clone $this;
        $new->headers->remove($name);

        return $new;
    }

    /**
     * @param array<string>|string $value
     */
    private function validateHeader(string $name, array|string $value): void
    {
        $this->validateHeaderName($name);
        $this->validateHeaderValue($value);
    }

    /**
     * Validates a header name according to RFC 7230.
     *
     * @param string $name The header name to validate
     * @throws InvalidArgumentException If the header name is invalid
     */
    private function validateHeaderName(string $name): void
    {
        // Header field-name is token (visible ASCII except delimiters)
        if ($name === '' || preg_match('/^[!#$%&\'*+.^_`|~0-9A-Za-z-]+$/', $name) !== 1) {
            throw new InvalidArgumentException(
                "Invalid header name '{$name}'. Header names must be non-empty and contain only visible ASCII characters without delimiters."
            );
        }
    }

    /**
     * Validates a header value according to RFC 7230.
     *
     * @param string|string[] $value The header value to validate
     * @throws InvalidArgumentException If the header value is invalid
     */
    private function validateHeaderValue(array|string $value): void
    {
        if (!count((array)$value)) {
            throw new InvalidArgumentException(
                'Header values must not be empty'
            );
        }
        foreach ((array)$value as $item) {
            if (!is_string($item) && !is_numeric($item)) {
                throw new InvalidArgumentException(
                    'Header values must be strings or numbers'
                );
            }

            $item = (string)$item;

            // RFC 7230 defines field-content as field-vchar with optional whitespace
            // field-vchar = VCHAR / obs-text
            // VCHAR = 0x21-0x7E (visible ASCII)
            // obs-text = 0x80-0xFF (extended ASCII)
            // SP = space (0x20)
            // HTAB = horizontal tab (0x09)
            if (preg_match('/[^\x09\x20\x21-\x7E\x80-\xFF]/', $item)) {
                throw new InvalidArgumentException(
                    'Header values must contain only visible characters and spaces'
                );
            }
        }
    }
}
