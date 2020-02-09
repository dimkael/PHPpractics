<?php

function getFiles($dir) {
    $files = array_diff(scandir($dir), ['..', '.']);
    $result = [];

    foreach ($files as $file) {
        $path = $dir.'/'.$file;
        if (is_dir($path))
            $result = array_merge($result, getFiles($path));
        else
            $result[] = $path;
    }

    return $result;
}