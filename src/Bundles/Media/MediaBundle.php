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

use Monolog\Logger;
use Paru\Bundles\Media\Controllers\Backend\CreateMediaController;
use Paru\Bundles\Media\Controllers\Backend\DeleteMediaController;
use Paru\Bundles\Media\Controllers\Backend\FindMediaController;
use Paru\Bundles\Media\Controllers\Backend\ListMediaController;
use Paru\Bundles\Media\Controllers\Backend\UpdateMediaController;
use Paru\Core\Bundle;
use Paru\Core\File\DirectoryHandler;
use Psr\Container\ContainerInterface;
use function DI\autowire;
use function DI\get;

/**
 * Description of MediaBundle
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
class MediaBundle implements Bundle {

    public function getRoutes(): array {
        return [
            'post' => [
                '[/]' => CreateMediaController::class
            ],
            'get' => [
                '[/]' => ListMediaController::class,
                '{params:.*}' => FindMediaController::class,
            ],
            'put' => [
                '[{params:.*}]' => UpdateMediaController::class
            ],
            'delete' => [
                '[{params:.*}]' => DeleteMediaController::class
            ]
        ];
    }
    
    public function getServices(): array {
        return [
            'paru.bundles.media.logger' => function(ContainerInterface $c) {
                return new Logger('bundles.media');
            },
            'paru.bundles.media.directoryhandler' => autowire(DirectoryHandler::class)
                    ->constructorParameter('searchPath', './files'),
            MediaFiles::class => autowire(MediaFiles::class)
                    ->constructor(
                            get('paru.bundles.media.logger'),
                            get('paru.bundles.media.directoryhandler')),
        ];
    }

}
