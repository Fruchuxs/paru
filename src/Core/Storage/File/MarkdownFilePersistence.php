<?php

use Paru\Core\File\DirectoryHandlerByMimeTypeFactory;
use Paru\Core\Storage\Data;
use Paru\Core\Storage\DataPersistence;
use Symfony\Component\Yaml\Yaml;

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

namespace Paru\Core\Storage\File;

/**
 * Description of MarkdownFilePersistence
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
class MarkdownFilePersistence implements DataPersistence {

    /**
     * @var DirectoryHandlerFactory
     */
    private $directoryHandlerFactory;

    public function __construct(DirectoryHandlerFactory $directoryHandlerFactory) {
        $this->directoryHandlerFactory = $directoryHandlerFactory;
    }

    public function save(Data $data, bool $overrideExisting = false): void {
        // TODO: replace through injected version ..
        $yaml = Yaml::dump($data->getMeta());
        $frontMatter = "---\n" . $yaml . "\n---\n" . $data->getPayload();

        $handler = $this->directoryHandlerFactory->create('text/markdown');
        $handler->saveFile($data->getUniqueName() . '.md', $frontMatter, $overrideExisting);
    }

}
