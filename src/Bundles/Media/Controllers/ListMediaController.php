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

namespace Paru\Bundles\Media\Controllers;

use Paru\Bundles\Media\MediaFiles;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Serializer\Serializer;

/**
 * Description of ListUpdateController
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
class ListMediaController {

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var MediaFiles
     */
    private $files;

    public function __construct(MediaFiles $files, Serializer $serializer) {
        
        $this->files = $files;
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request, Response $response, ?string $path = null) {
        $data = [];
        $files = $this->files->listFiles($path);
        $result = $this->serializer->serialize($files, 'json');
        $response->getBody()->write($result);
        
        return $response;
    }

}
