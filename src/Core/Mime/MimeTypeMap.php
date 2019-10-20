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

namespace Paru\Core\Mime;

/**
 * Holds paths for mime types.
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
class MimeTypeMap implements \ArrayAccess {

    /**
     * @var array
     */
    private $mimeMap;

    public function __construct(array $mimeMap) {
        $this->mimeMap = $mimeMap;
    }

    public function offsetExists($mimeTypeList): bool {
        $result = false;
        $this->doOperation($mimeTypeList, function($mime) use(&$result) {
            if ($this->getMappingValue($mime) !== null) {
                $result = true;
                return false;
            }
        });

        return $result;
    }

    public function offsetGet($mime) {
        return $this->getMappingValue($mime);
    }

    private function getMappingValue(string $mime): ?string {
        if (array_key_exists($mime, $this->mimeMap)) {
            return $this->mimeMap[$mime];
        }

        $slashNeedle = strpos($mime, '/');
        if ($slashNeedle !== false) {
            $lookForMimeWildCard = substr($mime, 0, $slashNeedle) . '/*';

            if (array_key_exists($lookForMimeWildCard, $this->mimeMap)) {
                return $this->mimeMap[$lookForMimeWildCard];
            }
        }

        return null;
    }

    public function offsetSet($mimeTypeList, $value): void {
        $this->doOperation($mimeTypeList, function($mime) use ($value) {
            $this->mimeMap[$mime] = $value;
        });
    }

    public function offsetUnset($mimeTypeList): void {
        $this->doOperation($mimeTypeList, function($mime) {
            unset($this->mimeMap[$mime]);
        });
    }

    private function doOperation($mimeTypeList, Callable $operation) {
        if (!$this->validateMimeType($mimeTypeList)) {
            throw new InvalidArgumentException('No valid mime Type given (was of type ' . gettype($mimeTypeList) . ' with value "' . $mimeTypeList . '").');
        }

        $this->forEachMime($mimeTypeList, function($mime) use($operation) {
            return $operation($mime);
        });
    }

    private function forEachMime(string $mimeTypesList, Callable $iterateCallback) {
        $exploded = explode(',', $mimeTypesList);
        foreach ($exploded as $mime) {
            if ($iterateCallback(trim($mime)) === false) {
                break;
            }
        }
    }

    private function validateMimeType(string $mime): bool {
        return is_string($mime) &&
                preg_match('/^((([a-z]+[a-z0-9\-_]*)|\*)\/(([a-z]+[a-z0-9\-_]*)|\*))(, ?(([a-z]+[a-z0-9\-_]*)\/(([a-z]+[a-z0-9\-_]*)|\*)))*$/iU', $mime);
    }

}
