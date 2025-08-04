<?php declare(strict_types=1);

namespace Borsch\Http;

use Borsch\Http\Traits\{Attribute, CookieParams, ParsedBody, QueryParams, ServerParams, UploadedFiles};
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Http\Message\{ServerRequestInterface, StreamInterface, UploadedFileInterface, UriInterface};

class ServerRequest extends Request implements ServerRequestInterface
{

    use Attribute, CookieParams, ParsedBody, QueryParams, ServerParams, UploadedFiles;

    /**
     * @inheritDoc
     *
     * @param array<string, string> $server_params
     * @param array<string, string> $cookie_params
     * @param UploadedFileInterface[] $uploaded_files
     */
    public function __construct(
        string $method,
        UriInterface $uri,
        ?StreamInterface $body = null,
        array $headers = [],
        string $protocol = '1.1',
        array $server_params = [],
        array $cookie_params = [],
        array $uploaded_files = []
    ) {
        parent::__construct($method, $uri, $body, $headers, $protocol);

        $this->server_params = $server_params;
        $this->cookie_params = $cookie_params;
        $this->uploaded_files = $uploaded_files;

        $this->attributes = new ArrayCollection();
    }
}
