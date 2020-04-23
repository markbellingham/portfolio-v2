<?php

class Functions {

    /**
     * Given a filename, replaces it with a string containing the filename and the file's modified time
     * Solves the browser caching issue, forcing the browser to reload when a file is modified
     * Works with JS, CSS, or any file that may be updated. Does not work with dynamically generated files.
     * Control file dev_settings.md allows for debugging on the development machine - do not upload this to the server
     * @param string $file - The file to be loaded. Must be an absolute path (i.e. starting with a slash)
     * @return string
     */
    public function auto_version(string $file)
    {
        if(strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'] . $file)) {
            return $file;
        }
        if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/dev_settings.md')) {
            return $file;
        }
        $mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
        return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
    }
}