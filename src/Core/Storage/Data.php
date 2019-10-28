<?php

namespace Paru\Core\Storage;

use Exception;
use Paru\Core\Mime\MimeTypeHelper;

class Data {

    use MimeTypeHelper;

    private $uniqueName;
    private $payload;
    private $mimeType;
    private $meta;

    public function __construct(?string $uniqueName = null, $payload = null, ?string $mimeType = null, array $meta = array()) {
        if (!$this->isValidUniqueName($uniqueName)) {
            throw new Exception('Given name is not valid.');
        }

        if (!$this->isValidMimeType($mimeType)) {
            throw new Exception('Given mimetype is not valid.');
        }

        $this->uniqueName = $uniqueName;
        $this->payload = $payload;
        $this->mimeType = $mimeType;
        $this->meta = $meta;
    }

    private function isValidUniqueName(?string $uniqueName): bool {
        return $uniqueName === null || \preg_match('/^[a-z0-9.\-_]+$/i', $uniqueName);
    }

    /**
     * Unique name of the data.
     * The name must be fit the following rule ^[a-z0-9.\-_]+$
     */
    public function getUniqueName(): ?string {
        return $this->uniqueName;
    }

    /**
     * The mime type of the data. Like text/markdown.
     * Should not be null.
     */
    public function getMimeType(): ?string {
        return $this->mimeType;
    }

    /**
     * Additional Meta data.
     * The array can be empty.
     */
    public function getMeta(): ?array {
        return $this->meta;
    }

    /**
     * The real data to store.
     */
    public function getPayload() {
        return $this->payload;
    }

    public function setUniqueName($uniqueName): Data {
        $this->uniqueName = $uniqueName;
        return $this;
    }

    public function setPayload($payload): Data {
        $this->payload = $payload;
        return $this;
    }

    public function setMimeType($mimeType): Data {
        $this->mimeType = $mimeType;
        return $this;
    }

    public function setMeta($meta): Data {
        $this->meta = $meta;
        return $this;
    }

}
