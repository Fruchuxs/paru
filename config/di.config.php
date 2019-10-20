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

use Paru\Core\Mime\MimeTypeMap;
use Paru\Core\Storage\DataPersistence;
use Paru\Core\Storage\File\DefaultFilePersistence;
use Paru\Core\Storage\File\MarkdownFilePersistence;
use Paru\Core\Storage\StorageComposite;
use function DI\autowire;
use function DI\get;

return [
    // Symfony serializer
    'symfony.serializer.encoders.xml' => autowire(Symfony\Component\Serializer\Encoder\XmlEncoder::class),
    'symfony.serializer.encoders.json' => autowire(Symfony\Component\Serializer\Encoder\JsonEncoderw::class),
    'symfony.serializer.normalizers.objectnormalizer' => autowire(\Symfony\Component\Serializer\Normalizer\ObjectNormalizer::class),
    'symfony.serializer.encoders' => [
        get('symfony.serializer.encoders.xml'),
        get('symfony.serializer.encoders.json'),
    ],
    'symfony.serializer.normalizers' => [
        get('symfony.serializer.normalizers.objectnormalizer'),
    ],
    Symfony\Component\Serializer\Serializer::class => autowire(Symfony\Component\Serializer\Serializer::class)
        ->constructor(get('symfony.serializer.normalizers'), get('symfony.serializer.encoders')),
    
    // where to store the files of a specific mime type
    'paru.persistence.paths.mime' => [
        'image/*' => '../files/images',
        'text/*' => '../files/documents',
        'application/*' => '../files/others'
    ],
    
     MimeTypeMap::class => autowire()
        ->constructor(get('paru.persistence.paths.mime')),
    
    // persistence strategies by mime type
    'paru.persistence.strategies' => [
        'text/markdown' => autowire(MarkdownFilePersistence::class),
    ],
    
    // persistence fallback strategy
    'paru.persistence.strategies.default' => autowire(DefaultFilePersistence::class),
    
    DataPersistence::class => autowire(StorageComposite::class)
        ->constructor(get('paru.persistence.strategies.default'),
                get('paru.persistence.strategies')),
];
