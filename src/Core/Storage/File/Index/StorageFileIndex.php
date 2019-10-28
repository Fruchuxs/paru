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

namespace Paru\Core\Storage\File\Index;

use DateTime;
use InvalidArgumentException;
use Paru\Core\Mime\MimeTypeHelper;
use Paru\Core\Storage\File\DirectoryHandler;
use Paru\Core\Storage\Index\IndexItem;
use Paru\Core\Storage\Index\NormalizeIndexName;

/**
 * Description of StorageFileIndex
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
class StorageFileIndex {

    use MimeTypeHelper;
    use NormalizeIndexName;

    /**
     * @var string|null
     */
    private $indexFileName;

    /**
     * @var DirectoryHandler
     */
    private $directoryHandler;
    private $indexList;
    private $loadDate;
    private $deleted;
    private $added;

    public function __construct(DirectoryHandler $directoryHandler, ?string $indexFileName = null) {
        $this->directoryHandler = $directoryHandler;
        $this->indexFileName = (empty($indexFileName) ? 'index' : $indexFileName) . '.json';
        $this->deleted = [];
        $this->added = [];
    }

    public function getIndex(string $name): IndexItem {
        $indexList = $this->getIndexList();
        $normalizedIndexName = $this->normalizeName($name);
        $mimeType = $indexList[$normalizedIndexName];

        $index = new IndexItem();
        return $index->setName($normalizedIndexName)
                        ->setMimeType($mimeType);
    }

    public function tryCreate(IndexItem $index): bool {
        if ($this->exists($index->getName())) {
            return false;
        }

        $this->create($index);

        return true;
    }

    public function create(IndexItem $index): void {
        if ($this->exists($index->getName())) {
            throw new InvalidArgumentException("Index '{$index->getName()}' already exists.");
        }

        $indexList = $this->getIndexList();
        $normalizedIndexName = $this->normalizeName($index->getName());

        $indexList[$normalizedIndexName] = [
            'mimeType' => $index->getMimeType(),
            'createDate' => new DateTime(),
        ];
        $this->added[$normalizedIndexName] = &$indexList[$normalizedIndexName];
        $this->mergeIndexListBack();
    }

    public function tryDelete(string $name): bool {
        if (!$this->exists($name)) {
            return false;
        }

        $this->delete($name);

        return true;
    }

    public function delete(string $name): void {
        if (!$this->exists($name)) {
            throw new InvalidArgumentException("Unknown index '$name'.");
        }

        $indexList = $this->getIndexList();
        $normalizedIndexName = $this->normalizeName($name);

        unset($indexList[$normalizedIndexName]);
        $this->deleted[$normalizedIndexName] = [
            'deleteDate' => new DateTime()
        ];
        $this->mergeIndexListBack();
    }

    public function exists(string $name): bool {
        $normalizedIndexName = $this->normalizeName($name);

        return array_key_exists($normalizedIndexName, $this->indexList);
    }

    private function mergeIndexListBack(): void {
        $loaded = $this->loadFromFile();
        $indexList = $this->getIndexList();

        $isAdded = function($key) use($loaded) {
            // TODO: check if create date of added is higher as file date
            return !array_key_exists($key, $loaded) && $this->added[$key];
        };
        
        $isDeleted = function($key) use($loaded) {
            return !array_key_exists($key, $loaded) || (array_key_exists($key, $this->deleted) && 
                    $loaded[$key]['createDate'] <= $this->deleted['deleteDate']);
        };
        
        $existInBoth = function($key) use($loaded, $indexList) {
            return array_key_exists($key, $loaded) && array_key_exists($key, $indexList);
        };
        
        $result = [];
        foreach ($indexList as $key => $data) {
            if ($existInBoth($key) && $isAdded($key) && !$isDeleted($key)) {     
                $result[$key] = $data;
            } 
        }
        
        $resultJson = json_encode($result);
        $this->directoryHandler->saveFile($this->indexFileName, $resultJson, true);
    }

    private function &getIndexList(): array {
        if ($this->indexList === null) {

            $this->indexList = $this->loadFromFile();
            $this->loadDate = new DateTime();
        }

        return $this->indexList;
    }

    private function &loadFromFile(): array {
        $indexFile = $this->directoryHandler->getFileContent($this->indexFileName);

        return json_decode($indexFile, true);
    }

}
