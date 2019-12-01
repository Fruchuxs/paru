<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Paru\Core\Storage;

use Exception;
use Paru\Core\Storage\Index\IndexItem;
use Paru\Core\Storage\Index\StorageIndex;

/**
 * Description of StorageComposite
 *
 * @author fruchuxs <fruchuxs@gmail.com>
 */
class StorageComposite implements DataPersistence {

    /**
     * @var StorageIndex
     */
    private $index;

    /**
     * @var DataPersistence
     */
    private $defaultStrategy;

    /**
     * @var array
     */
    private $mimeTypeStorageStrategies;

    public function __construct(DataPersistence $defaultStrategy, StorageIndex $index, array $mimeTypeStorageStrategies) {
        $this->defaultStrategy = $defaultStrategy;
        $this->index = $index;
        $this->mimeTypeStorageStrategies = $mimeTypeStorageStrategies;
    }

    public function save(Data $data, bool $overrideExisting = false): void {
        $mime = $this->escapeMimeType($data->getMimeType());
        
        if(!$overrideExisting && $this->index->exists($data->getUniqueName())) {
            throw new \Exception("Data with name {$data->getUniqueName()} already exists.");
        }
        
        $indexEntry = new IndexItem();
        $indexEntry->setMimeType($mime)
                ->setName($data->getUniqueName());
        
        if(!$this->index->tryCreate($indexEntry)) {
            throw new \Exception("Data with name ${$data->getUniqueName()} already exists - it was created before i can save the other data.");
        }
        
        try {
            $strategy = $this->GetStrategy($mime);
            $strategy->save($data, $overrideExisting);
        } catch (Exception $ex) {
            $this->index->tryDelete($data->getUniqueName());
            
            throw new Exception("Save failed.: $ex");
        } 
    }

    private function GetStrategy(string $mimeType): DataPersistence {
        return isset($this->mimeTypeStorageStrategies[$mimeType]) ?
                $this->mimeTypeStorageStrategies[$mimeType] :
                $this->defaultStrategy;
    }

    private function escapeMimeType(string $mimeType): string {
        $clearedMime = $mimeType !== null ? trim($mimeType) : $mimeType;
        if (empty($clearedMime)) {
            throw new Exception('Mime type was empty!');
        }

        return strtolower($clearedMime);
    }

}
