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

namespace Paru\Bundles\Page;

use Paru\Bundles\Page\Controllers\CreatePageController;
use Paru\Bundles\Page\Controllers\DeletePageController;
use Paru\Bundles\Page\Controllers\GetPageController;
use Paru\Bundles\Page\Controllers\ListPageController;
use Paru\Bundles\Page\Controllers\UpdatePageController;
use Paru\Core\Bundle;

/**
 * Description of PageBundle
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
class PageBundle implements Bundle {

    public function getServices(): array {
        return [];
    }

    public function getRoutes(): array {
        return [
            'get' => [
                '[/]' => ListPageController::class,
                '/{name}' => GetPageController::class
            ],
            'post' => [
                '[/]' => CreatePageController::class
            ],
            'put' => [
              '/{name}' => UpdatePageController::class  
            ],
            'delete' => [
                '/{name}' => DeletePageController::class
            ]
        ];
    }

}
