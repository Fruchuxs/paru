<?php

namespace Paru\Core\Storage;

interface DataPersistence {

    public function save(Data $data, bool $overrideExisting = false): void;
}
