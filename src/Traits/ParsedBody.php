<?php declare(strict_types=1);

namespace Borsch\Http\Traits;

use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use SimpleXMLElement;
use function filter_input_array, json_decode, parse_str, simplexml_load_string, str_contains;

trait ParsedBody
{

    /** @var array<int|string, mixed>|mixed $parsed_body */
    private mixed $parsed_body = null;

    /**
     * @inheritDoc
     *
     * @return array<int|string, mixed>|mixed
     */
    public function getParsedBody(): mixed
    {
        if ($this->parsed_body !== null) {
            return $this->parsed_body;
        }

        $content_type = $this->getHeaderLine('Content-Type');

        if ($this->method === 'POST' && (str_contains($content_type, 'application/x-www-form-urlencoded') || str_contains($content_type, 'multipart/form-data'))) {
            $post = filter_input_array(INPUT_POST);
            if ($post === false) {
                $post = [];
            }

            $this->parsed_body = $post;
        } elseif (in_array($this->method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $body = (string)$this->getBody();

            if (str_contains($content_type, 'application/json')) {
                $this->parsed_body = json_decode($body, true);
            } elseif (str_contains($content_type, 'application/xml') || str_contains($content_type, 'text/xml')) {
                $xml = simplexml_load_string($body);
                if ($xml instanceof SimpleXMLElement) {
                    $this->parsed_body = $xml;
                }
            } elseif (str_contains($content_type, 'application/x-www-form-urlencoded') || str_contains($content_type, 'multipart/form-data')) {
                parse_str($body, $this->parsed_body);
            }
        }

        return $this->parsed_body;
    }

    /**
     * @inheritDoc
     *
     * @param array<string, string|string[]>|object|null $data
     */
    public function withParsedBody($data): ServerRequestInterface
    {
        if (!is_array($data) && !is_object($data) && $data !== null) {
            throw new InvalidArgumentException('Parsed body must be an array, object, or null.');
        }

        $new = clone $this;
        $new->parsed_body = $data;

        return $new;
    }
}
