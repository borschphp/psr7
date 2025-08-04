<?php declare(strict_types=1);

namespace Borsch\Http;

use Borsch\Http\Traits\StatusCode;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response extends Message implements ResponseInterface
{

    use StatusCode;

    public function __construct(
        int $status_code = 200,
        ?StreamInterface $body = null,
        array $headers = [],
        ?string $reason_phrase = null,
        string $protocol = '1.1',
    ) {
        parent::__construct($protocol, $body, $headers);

        $this->status_code = $status_code;
        $this->reason_phrase = $reason_phrase ?? self::$phrases[$status_code] ?? '';
    }
}
