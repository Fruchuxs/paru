<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Paru\Core\Storage\File;

/**
 * Description of FileHelper
 *
 * @author fruchuxs <fruchuxs@gmail.com>
 */
class PathHelper {

    private const RemoveCharacters = " \t\n\r\0\x0B/";
    private const FileDelimiter = '/';

    public function joinPaths(...$path): string {
        if (empty($path)) {
            throw new InvalidArgumentException("Given path was empty");
        }

        $combinedPath = '';
        $stack = $path;
        for ($element = array_pop($stack); $element !== null; $element = array_pop($stack)) {
            if (is_array($element)) {
                $stack = array_merge($stack, $element);
            } elseif (is_string($element)) {
                $escapedElement = trim($element, self::RemoveCharacters);

                if (empty($escapedElement)) {
                    throw new InvalidArgumentException('One of the pieces is empty.');
                }

                $combinedPath = $escapedElement . self::FileDelimiter . $combinedPath;
            } else {
                throw new InvalidArgumentException('One of the pieces is not a string nor an array of strings ("' . gettype($element) . '" given).');
            }
        }

        return rtrim($combinedPath, self::FileDelimiter);
    }

}
