<?php

/*
 * Copyright 2019 fruchuxs <fruchuxs@gmail.com>.
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

namespace Paru\Core\Content;

/**
 * Description of TextFormatter
 *
 * @author fruchuxs <fruchuxs@gmail.com>
 */
interface HtmlFormatter {

    /**
     * Mime Type of the input text which will format to html.
     *
     * @return string
     */
    function getMimeType(): string;

    /**
     * Formats the text to html.
     *
     * @param string $text
     * @return string HTML formatted content.
     */
    function toHtml(string $text): string;
}
