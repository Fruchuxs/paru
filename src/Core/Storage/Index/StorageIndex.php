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

namespace Paru\Core\Storage\Index;


/**
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
interface StorageIndex {
    public function getIndex(string $name): IndexItem;

    public function tryCreate(IndexItem $index): bool;

    public function create(IndexItem $index): void;

    public function tryDelete(string $name): bool;

    public function delete(string $name): void;

    public function exists(string $name): bool;
}
