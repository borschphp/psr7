<?php declare(strict_types=1);

namespace Borsch\Http\Response;

use Borsch\Http\Response;
use RuntimeException;

class JsonResponse extends Response
{

    /**
     * @param array<string|int, mixed>|object $data
     */
    public function __construct(array|object $data, int $status_code = 200, array $headers = [])
    {
        parent::__construct($status_code, headers: $headers);

        $json = json_encode(
            $data,
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES
        );

        if ($json === false) {
            throw new RuntimeException('Failed to encode data to JSON: ' . json_last_error_msg());
        }

        $this->getBody()->write($json);
        $this->headers->set('Content-Type', ['application/json; charset=UTF-8']);
    }
}
