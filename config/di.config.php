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

use Paru\Core\File\PathHelper;
use Paru\Core\File\MimeTypeMap;
use Paru\Core\File\DirectoryHandler;
use Paru\Core\Storage\DataFinder;
use Paru\Core\Storage\DataFinderComposite;
use Paru\Core\Storage\DataPersistence;
use Paru\Core\Storage\File\DefaultFilePersistence;
use Paru\Core\Storage\File\MarkdownFilePersistence;
use Paru\Core\Storage\Index\FileStorageIndex;
use Paru\Core\Storage\Index\StorageIndex;
use Paru\Core\Storage\StorageComposite;
use Psr\Container\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use function DI\autowire;
use function DI\get;

return [
    // Symfony serializer
    'symfony.serializer.encoders.xml' => autowire(XmlEncoder::class),
    'symfony.serializer.encoders.json' => autowire(JsonEncoder::class),
    ObjectNormalizer::class => autowire(ObjectNormalizer::class),
    'symfony.serializer.encoders' => [
        get('symfony.serializer.encoders.xml'),
        get('symfony.serializer.encoders.json'),
    ],
    'symfony.serializer.normalizers' => [
        get(ObjectNormalizer::class),
    ],
    Serializer::class => autowire(Serializer::class)
            ->constructor(get('symfony.serializer.normalizers'), get('symfony.serializer.encoders')),
    PathHelper::class => autowire(PathHelper::class),
    'paru.storage.index.fileName' => 'storage_index',
    StorageIndex::class => function(ContainerInterface $c) {
        $directoryHandler = new DirectoryHandler('../files/internal', $c->get(PathHelper::class));

        return new FileStorageIndex($directoryHandler, $c->get('paru.storage.index.fileName'));
    },
    // where to store the files of a specific mime type
    'paru.storage.paths.mime' => [
        'image/*' => '../files/data/images',
        'text/*' => '../files/data/documents',
        '*/*' => '../files/data/general',
    ],
    MimeTypeMap::class => autowire()
            ->constructor(get('paru.storage.paths.mime')),
    // persistence strategies by mime type
    'paru.storage.save.strategies' => [
        'text/markdown' => autowire(MarkdownFilePersistence::class),
    ],
    // persistence fallback strategy
    'paru.storage.save.strategies.default' => autowire(DefaultFilePersistence::class),
    DataPersistence::class => autowire(StorageComposite::class)
            ->constructor(get('paru.storage.save.strategies.default'),
                    get(StorageIndex::class),
                    get('paru.storage.save.strategies')),
    // find things in storage
    'paru.storage.find.strategies' => [
        '*/*' => autowire(DefaultFilePersistence::class),
    ],
    DataFinder::class => autowire(DataFinderComposite::class)
            ->constructor(get(StorageIndex::class), get('paru.storage.find.strategies')),

];
