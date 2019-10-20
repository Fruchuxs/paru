<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Paru\Core\Storage\File;

/**
 * Retrieve and store files in a specific directory.
 *
 * @author fruchuxs <fruchuxs@gmail.com>
 */
class DirectoryHandler {

    /**
     * @var PathHelper
     */
    private $pathHelper;

    /**
     * @var string
     */
    private $searchPath;

    public function __construct(string $searchPath, PathHelper $pathHelper) {

        $this->searchPath = $searchPath;
        $this->pathHelper = $pathHelper;
    }

    public function saveFile(string $name, $content, bool $overrideExistsing = false): void {
        $path = $this->pathHelper->joinPaths($this->searchPath, $name);
        if (!$overrideExistsing && file_exists($path)) {
            throw new \Exception("File $path already exists.");
        }

        file_put_contents($path, $content);
    }

    public function getFile(string $name): string {
        $path = $this->pathHelper->joinPaths($this->searchPath, $name);

        if (!file_exists($path)) {
            throw new InvalidArgumentException('File "' . $path . '" doesn\'t exists.');
        }

        return file_get_contents($path);
    }

}
