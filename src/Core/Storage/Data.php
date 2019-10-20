<?php

namespace Paru\Core\Storage;

class Data {

    private $uniqueName;
    private $payload;
    private $mimeType;
    private $meta;

    public function __construct(string $uniqueName, $payload, string $mimeType, array $meta = array()) {
        $this->uniqueName = $uniqueName;
        $this->payload = $payload;
        $this->mimeType = $mimeType;
        $this->meta = $meta;
    }

    /**
     * Unique name of the data.
     * The name must be fit the following rule ^[a-z0-9.\-_}+$
     */
    public function getUniqueName(): string {
        return $this->uniqueName;
    }

    /**
     * The mime type of the data. Like text/markdown.
     * Should not be null.
     */
    public function getMimeType(): string {
        return $this->mimeType;
    }

    /**
     * Additional Meta data.
     * The array can be empty.
     */
    public function getMeta(): array {
        return $this->meta;
    }

    /**
     * The real data to store.
     */
    public function getPayload() {
        return $this->payload;
    }

}
