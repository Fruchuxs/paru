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

namespace Paru\Core\Storage;

use Paru\Core\Storage\Index\StorageIndex;
use TheSeer\Tokenizer\Exception;

/**
 * Description of DataFinderComposite
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
class DataFinderComposite implements DataFinder {

    /**
     * @var array
     */
    private $mimetypeStrategies;

    /**
     * @var StorageIndex
     */
    private $index;

    // TODO: maybe add a default strategy
    public function __construct(StorageIndex $index, array $mimetypeStrategies) {
        $this->index = $index;
        $this->mimetypeStrategies = $mimetypeStrategies;
    }

    public function find(string $uniqueName): Data {

        if (!$this->index->exists($uniqueName)) {
            throw new Exception("Data '$uniqueName' does not exist.");
        }

        $index = $this->index->getIndex($uniqueName);
        $strategy = $this->getFinderStrategy($index->getMimeType());

        return $strategy->find($uniqueName);
    }

    public function list(string $mimeType, DataCondition $condition): array {
        $strategy = $this->getFinderStrategy($mimeType);

        return $strategy->list($condition);
    }

    private function getFinderStrategy(string $mimeType): DataFinder {
        $searchForMime = $mimeType;
        
        if(!array_key_exists($searchForMime, $this->mimetypeStrategies)) {
            $searchForMime = '*/*';
        } else {
            return $this->mimetypeStrategies[$mimeType];
        }
        
        if (!array_key_exists($searchForMime, $this->mimetypeStrategies)) {
            throw new Exception("No strategy for mime type '$mimeType' registred.");
        }

        return $this->mimetypeStrategies[$mimeType];
    }

}
