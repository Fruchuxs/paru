<?php

namespace Paru\Bundles\Page;

class Page {

    private $name;
    private $createDate;
    private $contentType;
    private $content;

    public function __construct() {
        $this->createDate = new \DateTime();
    }

    public function getContent(): string {
        return $this->content;
    }

    public function setContent($content): Page {
        $this->content = $content;

        return $this;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): Page {
        $this->name = $name;

        return $this;
    }

    public function getCreateDate(): \DateTime {
        return $this->createDate;
    }

    public function setCreateDate(\DateTime $createDate): Page {
        $this->createDate = $createDate;

        return $this;
    }

    function getContentType() {
        return $this->contentType;
    }

    function setContentType($contentType) {
        $this->contentType = $contentType;
        return $this;
    }

}
