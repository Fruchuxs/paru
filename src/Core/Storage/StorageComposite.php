<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Paru\Core\Storage;

/**
 * Description of StorageComposite
 *
 * @author fruchuxs <fruchuxs@gmail.com>
 */
class StorageComposite implements DataPersistence {

    /**
     * @var DataPersistence
     */
    private $defaultStrategy;

    /**
     * @var array
     */
    private $mimeTypeStorageStrategies;

    public function __construct(DataPersistence $defaultStrategy, array $mimeTypeStorageStrategies) {

        $this->defaultStrategy = $defaultStrategy;
        $this->mimeTypeStorageStrategies = $mimeTypeStorageStrategies;
    }

    public function save(Data $data, bool $overrideExisting = false): void {
        $mime = $this->escapeMimeType($data->getMimeType());

        $strategy = $this->GetStrategy($mime);
        $strategy->save($data, $overrideExisting);
    }

    private function GetStrategy(string $mimeType): DataPersistence {
        return isset($this->mimeTypeStorageStrategies[$mimeType]) ?
                $this->mimeTypeStorageStrategies[$mimeType] :
                $this->defaultStrategy;
    }

    private function escapeMimeType(string $mimeType): string {
        $clearedMime = $mimeType !== null ? trim($mimeType) : $mimeType;
        if (empty($clearedMime)) {
            throw new \Exception('Mime type was empty!');
        }

        return strtolower($clearedMime);
    }

}
