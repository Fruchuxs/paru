<?php

/*
 * Copyright 2019 Fruchuxs <fruchuxs@gmail.com>.
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

namespace Paru\Core\Storage\Index;

use InvalidArgumentException;
use Paru\Core\File\MimeTypeHelper;

/**
 * Description of IndexItem
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
class IndexItem {

    use MimeTypeHelper;
    use NormalizeIndexName;

    private $name;
    private $mimeType;
    
    public function __construct() {
    }

    public function getName(): string {
        return $this->name;
    }

    public function getMimeType(): string {
        return $this->mimeType;
    }

    public function setName(string $name): IndexItem {
        $this->name = $this->normalizeName($name);
        return $this;
    }

    public function setMimeType(string $mimeType): IndexItem {
        if (!$this->isValidMimeType($mimeType)) {
            throw new InvalidArgumentException("'$mimeType' is not a valid mime type.");
        }

        $this->mimeType = $mimeType;
        return $this;
    }

}
