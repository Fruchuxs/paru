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

use Paru\Core\Bundle;
use Paru\Bundles\Page\Controllers\Backend\CreatePageController;
use Paru\Bundles\Page\Controllers\Backend\DeletePageController;
use Paru\Bundles\Page\Controllers\Backend\GetPageController;
use Paru\Bundles\Page\Controllers\Backend\ListPageController;
use Paru\Bundles\Page\Controllers\Backend\UpdatePageController;
use Slim\Routing\RouteCollectorProxy;

/**
 * Description of PageBundle
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
class PageBundle implements Bundle {
    
    public function getResourceName(): string {
        return 'pages';
    }

    public function getServices(): array {
        return [];
    }

    public function configureBackendRoutes(RouteCollectorProxy $backend): void {
        $backend->get('[/]', ListPageController::class);
        $backend->get('/{name}', GetPageController::class);
        $backend->post('[/]', CreatePageController::class);
        $backend->put('/{name}', UpdatePageController::class);
        $backend->delete('/{name}', DeletePageController::class);
    }

    public function configureFrontendRoutes(RouteCollectorProxy $frontend): void {
        
    }

}
