<?php

function removeDir($dir) {
    $files = array_diff(scandir($dir), ['..', '.']);

    foreach ($files as $file) {
        $path = $dir.'/'.$file;

        if (is_dir($path))
            removeDir($path);
        else
            unlink($path);
    }

    rmdir($dir);
}