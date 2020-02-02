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
use InvalidArgumentException;
use Paru\Core\File\DirectoryHandler;

/**
 * Description of ContentFiles
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
class MediaFiles {

    /**
     * @var DirectoryHandler
     */
    private $rootDirectory;

    public function __construct(DirectoryHandler $rootDirectory) {
        $this->rootDirectory = $rootDirectory;
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
            throw new InvalidArgumentException('Invalid Path given.');
        }

        if (!$this->rootDirectory->exists($path)) {
            return false;
        }

        try {
            $this->rootDirectory->deleteFile($path);
        } catch (Exception $ex) {
            throw new InvalidArgumentException('Can\'t delete file.');
        }
    }

}
