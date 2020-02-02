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

namespace Paru\Core\File;

/**
 * Description of MimeTypeHelper
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
trait MimeTypeHelper {
    private function isValidMimeType(string $mime): bool {
        return is_string($mime) &&
                preg_match('/^((([a-z]+[a-z0-9\-_]*)|\*)\/(([a-z]+[a-z0-9\-_]*)|\*))(, ?(([a-z]+[a-z0-9\-_]*)\/(([a-z]+[a-z0-9\-_]*)|\*)))*$/iU', $mime);
    }
}
