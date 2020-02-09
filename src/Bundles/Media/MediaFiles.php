<?php

/*
 * Copyright 2020 Fruchuxs <fruchuxs@gmail.com>.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Paru\Bundles\Media;

use BadMethodCallException;
use Exception;
use Paru\Core\File\DirectoryHandler;
use Psr\Log\LoggerInterface;

/**
 * Description of ContentFiles
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
class MediaFiles {

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var DirectoryHandler
     */
    private $rootDirectory;

    public function __construct(LoggerInterface $logger, DirectoryHandler $rootDirectory) {
        $this->rootDirectory = $rootDirectory;
        $this->logger = $logger;
    }

    public function listFiles(?string $path): array {
        $files = $this->rootDirectory->getFiles($path, true);
        
        $result = [];
        foreach ($files as $file) {
            $result[] = new MediaFileInfo($file, $this->rootDirectory->getSearchPath());
        }
        
        return $result;
    }
    
    public function findFile(string $path): ContentFileInfo {
        $this->rootDirectory->getFileContent($path);
    }

    public function saveFile(string $path, $data, ?array $metaInformation): void {
        if ($metaInformation !== null) {
            throw new BadMethodCallException('Meta Informations not implemented yet.');
        }

        $this->rootDirectory->saveFile($path, $data);
    }

    public function tryDeleteFile(string $path): bool {
        if (empty($path)) {
            $this->logger->warning('Given path was empty.');
            
            return false;
        }

        if (!$this->rootDirectory->exists($path)) {
            $this->logger->warning('Given path doesn\'t exists.');
            
            return false;
        }

        try {
            $this->rootDirectory->deleteFile($path);
            
            return true;
        } catch (Exception $ex) {
            $this->logger->error('Unknown error.', ['exception' => $ex]);
            
            return false;
        }
    }
}
