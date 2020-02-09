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

namespace Paru\Core;

/**
 * Description of BundlesLoader
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
class BundlesLoader {

    /**
     * @var array
     */
    private $bundles;

    public function __construct(array $bundles) {
        $this->bundles = $bundles;
    }

    public function registerServices(\Closure $registrator): void {
        $this->bundleLoop(function($name, $bundle) use($registrator) {
            $registrator($bundle->getServices());
        });
    }

    public function registerRoutes(\Slim\Routing\RouteCollectorProxy $collector): void {
        $this->bundleLoop(function($name, $bundle) use($collector) {
            $routeDefinition = $bundle->getRoutes();
            foreach ($routeDefinition as $method => $routes) {
                $collector->group("/$name", function(\Slim\Routing\RouteCollectorProxy $group) use($method, $routes) {
                    foreach ($routes as $path => $callable) {
                        $group->{$method}($path, $callable);
                    }
                });
            }
        });
    }
    
    private function bundleLoop(\Closure $callback) {
        foreach ($this->bundles as $name => $bundle) {
            if (!is_object($bundle)) {
                throw new Exception('Only objects are allowed');
            }

            if (!($bundle instanceof Bundle)) {
                throw new Exception('Wrong instance given.');
            }

            $callback($name, $bundle);
        }
    }

}
