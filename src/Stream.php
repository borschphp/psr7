<?php declare(strict_types=1);

namespace Borsch\Http;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use function fclose, feof, fread, fseek, fstat, ftell, fwrite, is_resource, stream_get_contents, stream_get_meta_data;

class Stream implements StreamInterface
{

    /** @var ?resource $stream */
    private $stream;
    private bool $seekable;
    private bool $readable;
    private bool $writable;

    private const READ_MODES = [
        'r' => true, 'r+' => true, 'w+' => true, 'a+' => true, 'x+' => true, 'c+' => true,
        'rb' => true, 'r+b' => true, 'w+b' => true, 'a+b' => true, 'x+b' => true, 'c+b' => true,
        'rt' => true, 'r+t' => true, 'w+t' => true, 'a+t' => true, 'x+t' => true, 'c+t' => true,
    ];

    private const WRITE_MODES = [
        'r+' => true, 'w' => true, 'w+' => true, 'a' => true, 'a+' => true, 'x' => true,
        'x+' => true, 'c' => true, 'c+' => true,
        'r+b' => true, 'wb' => true, 'w+b' => true, 'ab' => true, 'a+b' => true, 'xb' => true,
        'x+b' => true, 'cb' => true, 'c+b' => true,
        'r+t' => true, 'wt' => true, 'w+t' => true, 'at' => true, 'a+t' => true, 'xt' => true,
        'x+t' => true, 'ct' => true, 'c+t' => true,
    ];

    /**
     * @param resource $stream
     */
    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            throw new InvalidArgumentException('The provided stream is not a valid resource.');
        }

        $this->stream = $stream;

        /** @var array<string, mixed> $metadata */
        $metadata = $this->getMetadata();

        $this->seekable = (bool)($metadata['seekable'] ?? false);
        $this->readable = isset(self::READ_MODES[$metadata['mode'] ?? '']);
        $this->writable = isset(self::WRITE_MODES[$metadata['mode'] ?? '']);
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        if ($this->isSeekable()) {
            $this->rewind();
        }

        return $this->getContents();
    }

    /**
     * @inheritDoc
     */
    public function close(): void
    {
        if (isset($this->stream)) {
            if (is_resource($this->stream)) {
                fclose($this->stream);
            }

            $this->detach();
        }
    }

    /**
     * @inheritDoc
     */
    public function detach()
    {
        if (!isset($this->stream)) {
            return null;
        }

        $resource = $this->stream;

        unset($this->stream);

        $this->seekable = false;
        $this->readable = false;
        $this->writable = false;

        return $resource;
    }

    /**
     * @inheritDoc
     */
    public function getSize(): ?int
    {
        if (!isset($this->stream)) {
            return null;
        }

        $stats = fstat($this->stream);

        return $stats['size'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function tell(): int
    {
        if (!isset($this->stream)) {
            throw new RuntimeException('Stream is detached.');
        }

        $position = ftell($this->stream);
        if ($position === false) {
            throw new RuntimeException('Unable to determine the position of the stream.');
        }

        return $position;
    }

    /**
     * @inheritDoc
     */
    public function eof(): bool
    {
        return !isset($this->stream) || feof($this->stream);
    }

    /**
     * @inheritDoc
     */
    public function isSeekable(): bool
    {
        return $this->seekable;
    }

    /**
     * @inheritDoc
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!isset($this->stream)) {
            throw new RuntimeException('Stream is detached.');
        }

        if (!$this->isSeekable()) {
            throw new RuntimeException('Stream is not seekable.');
        }

        if (fseek($this->stream, $offset, $whence) === -1) {
            throw new RuntimeException('Unable to seek to the specified position in the stream.');
        }
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->seek(0);
    }

    /**
     * @inheritDoc
     */
    public function isWritable(): bool
    {
        return $this->writable;
    }

    /**
     * @inheritDoc
     */
    public function write(string $string): int
    {
        if (!isset($this->stream)) {
            throw new RuntimeException('Stream is detached.');
        }

        if (!$this->isWritable()) {
            throw new RuntimeException('Stream is not writable.');
        }

        $bytes = fwrite($this->stream, $string);
        if ($bytes === false) {
            throw new RuntimeException('Unable to write to the stream.');
        }

        return $bytes;
    }

    /**
     * @inheritDoc
     */
    public function isReadable(): bool
    {
        return $this->readable;
    }

    /**
     * @inheritDoc
     *
     * @param int<1, max> $length
     */
    public function read(int $length): string
    {
        if (!isset($this->stream)) {
            throw new RuntimeException('Stream is detached.');
        }

        if (!$this->isReadable()) {
            throw new RuntimeException('Stream is not readable.');
        }

        $data = fread($this->stream, $length);
        if ($data === false) {
            throw new RuntimeException('Unable to read from the stream.');
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function getContents(): string
    {
        if (!isset($this->stream)) {
            throw new RuntimeException('Stream is detached.');
        }

        if (!$this->isReadable()) {
            throw new RuntimeException('Stream is not readable.');
        }

        $contents = stream_get_contents($this->stream);
        if ($contents === false) {
            throw new RuntimeException('Unable to read contents from the stream.');
        }

        return $contents;
    }

    /**
     * @inheritDoc
     */
    public function getMetadata(?string $key = null)
    {
        if (!isset($this->stream)) {
            return null;
        }

        $metadata = stream_get_meta_data($this->stream);

        if ($key === null) {
            return $metadata;
        }

        return $metadata[$key] ?? null;
    }
}
