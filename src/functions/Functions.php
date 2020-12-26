<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$configs = require_once $_SERVER['DOCUMENT_ROOT'] .  '../config/config.php';

class Functions {

    /**
     * Given a filename, replaces it with a string containing the filename and the file's modified time
     * Solves the browser caching issue, forcing the browser to reload when a file is modified
     * Works with JS, CSS, or any file that may be updated. Does not work with dynamically generated files.
     * Control file dev_settings.md allows for debugging on the development machine - do not upload this to the server
     * @param string $file - The file to be loaded. Must be an absolute path (i.e. starting with a slash)
     * @return string
     */
    public function auto_version(string $file): string
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

    public function xml_encode(SimpleXMLElement $object, array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $new_object = $object->addChild($key);
                $this->xml_encode($new_object, (array) $value);
            } else {
                // if the key is an integer, it needs text with it to actually work.
                if ($key == (int) $key) {
                    $key = "key_$key";
                }
                $object->addChild($key, htmlspecialchars($value));
            }
        }
    }

    public function randomToken($length = 32): string
    {
        if(!isset($length) || intval($length) <= 8 ){
            $length = 32;
        }
        if (function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($length));
        }
    }

    public function requestedByTheSameDomain($secret): bool
    {
        return $secret === $_SESSION['server-secret'];
    }
}