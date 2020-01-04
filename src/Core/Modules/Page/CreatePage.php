<?php

namespace Paru\Core\Modules\Page;

use Paru\Core\Modules\Page\Page;
use Paru\Core\Storage\Data;
use Paru\Core\Storage\DataPersistence;

class CreatePage {

    protected $storage;

    public function __construct(DataPersistence $storage) {
        $this->storage = $storage;
    }

    public function SavePage(Page $page) {
        $data = new Data(
                $page->getName(),
                $page->getContent(),
                $page->getContentType(),
                [
            'createDate' => $page->getCreateDate(),
                ]
        );

        $this->storage->save($data);
    }

}
