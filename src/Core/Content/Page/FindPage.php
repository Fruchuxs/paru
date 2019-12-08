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

namespace Paru\Core\Content\Page;

use Paru\Core\Storage\DataFinder;

/**
 * Description of FindPage
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
class FindPage {

    /**
     * @var DataFinder
     */
    private $finder;

    public function __construct(DataFinder $finder) {
        $this->finder = $finder;
    }

    public function find($pageName) {
        $data = $this->finder->find($pageName);
        $meta = $data->getMeta();

        $createDate = null;
        if (array_key_exists('createDate', $meta)) {
            $dateInfo = $meta['createDate'];

            $createDate = new \DateTime();
            
            $createDate->setTimezone(new \DateTimeZone('UTC'));
            $createDate->setTimestamp($dateInfo['timestamp']);
        }

        $page = new Page();
        $page->setName($data->getUniqueName())
                ->setContent($data->getPayload())
                ->setContentType($data->getMimeType());

        if ($createDate !== null) {
            $page->setCreateDate($createDate);
        }

        return $page;
    }

}
