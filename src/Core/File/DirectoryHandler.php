<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Paru\Core\File;

use Exception;
use InvalidArgumentException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Retrieve and store files in a specific directory.
 *
 * @author fruchuxs <fruchuxs@gmail.com>
 */
class DirectoryHandler {

    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var PathHelper
     */
    private $pathHelper;

    /**
     * @var string
     */
    private $searchPath;

    // TODO: refactor to symfony filesystem
    public function __construct(string $searchPath, PathHelper $pathHelper, Finder $finder, Filesystem $fileSystem) {
        $this->searchPath = $searchPath;
        $this->pathHelper = $pathHelper;
        $this->finder = $finder;
        $this->fileSystem = $fileSystem;
    }

    public function saveFile(string $name, $content, bool $overrideExistsing = false): void {
        $path = $this->pathHelper->joinPaths($this->searchPath, $name);
        if (!$overrideExistsing && file_exists($path)) {
            throw new Exception("File $path already exists.");
        }

        $this->fileSystem->dumpFile($path, $content);
    }

    public function getFileContent(string $name) {
        $path = $this->pathHelper->joinPaths($this->searchPath, $name);
        if (!$this->exists($name)) {
            throw new InvalidArgumentException('File "' . $path . '" doesn\'t exists.');
        }

        return fopen($path, 'r');
    }

    public function getFileStream(string $name) {
        $path = $this->pathHelper->joinPaths($this->searchPath, $name);
        if (!$this->exists($name)) {
            throw new InvalidArgumentException('File "' . $path . '" doesn\'t exists.');
        }

        return fopen($path, 'r');
    }

    public function deleteFile(string $name): void {
        try {
            $this->fileSystem->remove($name);
        } catch (IOException $ex) {
            throw new InvalidArgumentException($ex->getMessage());
        }
    }

    /*
     * returns SPLFileInfo
     */

    public function getFiles(?string $subPath = null, bool $includeDirectories = false): array {
        $result = [];

        $files = $this->finder;
        if (!$includeDirectories) {
            $files->files();
        }

        $searchPath = empty($subPath) ?
                $this->searchPath :
                $this->pathHelper->joinPaths($this->searchPath, $subPath);

        $files->in($searchPath);
        foreach ($files as $file) {
            $result[] = $file;
        }

        return $result;
    }

    public function exists(string $name): bool {
        $path = $this->pathHelper->joinPaths($this->searchPath, $name);

        return $this->fileSystem->exists($path);
    }

    public function getSearchPath(): string {
        return $this->searchPath;
    }

}
