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

namespace Paru\Core\Storage;

/**
 * Description of DataCondition
 *
 * @author Fruchuxs <fruchuxs@gmail.com>
 */
class DataCondition {
    private $limit;
    private $offset;
    private $orderBy;
    private $whereCondition;
    function getLimit() {
        return $this->limit;
    }

    function getOffset() : ?int {
        return $this->offset;
    }

    function getOrderBy() : ?string {
        return $this->orderBy;
    }

    function getWhereCondition() : ?string {
        return $this->whereCondition;
    }

    function setLimit(int $limit) : DataCondition {
        $this->limit = $limit;
        return $this;
    }

    function setOffset(int $offset) : DataCondition {
        $this->offset = $offset;
        return $this;
    }

    function setOrderBy($orderBy) : DataCondition {
        $this->orderBy = $orderBy;
        return $this;
    }

    function setWhereCondition(string $whereCondition) : DataCondition {
        $this->whereCondition = $whereCondition;
        return $this;
    }
}
