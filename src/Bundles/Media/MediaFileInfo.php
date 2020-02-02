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

use SplFileInfo;

/**
 * Description of ContentFile
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
class MediaFileInfo {
    /**
     * @var string
     */
    private $basePath;

    /**
     * @var SplFileInfo
     */
    private $fileInfo;

    public function __construct(SplFileInfo $fileInfo, string $basePath) {
        $this->fileInfo = $fileInfo;
        $this->basePath = $basePath;
    }

    public function getName(): string {
        return $this->fileInfo->getFilename();
    }

    public function getSize(): int {
        return $this->fileInfo->getSize();
    }

    public function getDirectory(): string {
        $relativePath = substr($this->fileInfo->getPath(), strlen($this->basePath));
        
        if(empty($relativePath)) {
            return '/';
        }
        
        return str_replace('\\', '/', $relativePath);
    }
    
    public function getMimeType(): string{
        return mime_content_type($this->fileInfo->getPathname());
    }
    
    public function getPath(): string {
        return $this->getDirectory() . '/' . $this->getName();
    }
}
