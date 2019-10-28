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
declare(strict_types=1);

use Paru\Core\Mime\MimeTypeMap;
use Paru\Core\Storage\Data;
use Paru\Core\Storage\File\DefaultFilePersistence;
use Paru\Core\Storage\File\DirectoryHandlerFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Description of DataPersistenceTests
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
class DataPersistenceTests extends TestCase {

    private $directoryHandlerFactory;
    private $serializer;

    protected function setUp(): void {
        $mimeMap = [
            '*/*' => 'tests/assets',
        ];
        $mimeTypePaths = new MimeTypeMap($mimeMap);
        $this->directoryHandlerFactory = new DirectoryHandlerFactory($mimeTypePaths);

        $normalizers = [
            new ObjectNormalizer()
        ];
        $encoders = [
            new XmlEncoder(),
            new JsonEncoder(),
        ];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * 
     * @return void
     */
    public function testSaveData(): void {
        $defaultPersistence = new DefaultFilePersistence($this->directoryHandlerFactory, $this->serializer);

        $defaultPersistence->save(new Data("name_test-tmp", "hello world", "application/test"));

        $this->assertTrue(file_exists('tests/assets/name_test-tmp.json') &&
                file_get_contents('tests/assets/name_test-tmp.json') === '{"meta":{"payload_classname":null},"data":{"uniqueName":"name_test-tmp","mimeType":"application\/test","meta":[],"payload":"hello world"}}');
    }

    /**
     * 
     * @return void
     */
    public function testFindData(): void {
        $defaultPersistence = new DefaultFilePersistence($this->directoryHandlerFactory, $this->serializer);

        $data = $defaultPersistence->find('withoutPayload');
        
        $this->assertTrue($data->getUniqueName() === 'a_unique_name', "unique name was '{$data->getUniqueName()}', but 'withoutPayload' expected");
    }

    protected function tearDown(): void { 
        $files = glob('tests/assets/*');
        foreach ($files as $file) { 
            if (is_file($file) && preg_match('/^.+-tmp\..+$/', $file)) {
                unlink($file); 
            }
                
        }
    }

}
