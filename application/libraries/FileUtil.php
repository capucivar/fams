<?php

/**
 * Created by JetBrains PhpStorm.
 * User: YPL
 * Date: 13-9-23
 * Time: 上午10:23
 * To change this template use File | Settings | File Templates.
 */
class FileUtil {
    public function __construct() {
        parent::__construct();
    }

    public static function mkdirs($dir) {
        if (!is_dir($dir)) {
            if (!FileUtil::mkdirs(dirname($dir))) {
                return false;
            }
            if (!mkdir($dir, 0777)) {
                return false;
            }
        }
        return true;
    }

    public static function rmdirs($dir) {
        $d = dir($dir);
        while (false !== ($child = $d->read())) {
            if ($child != '.' && $child != '..') {
                if (is_dir($dir . '/' . $child))
                    FileUtil::rmdirs($dir . '/' . $child);
                else unlink($dir . '/' . $child);
            }
        }
        $d->close();
        rmdir($dir);
    }

    public static function delfile($fullpath) {
        if (is_file($fullpath)) {
            try {
                //chmod($fullpath,0777);
            } catch (Exception $e) {

            }

            if (unlink($fullpath)) {
                log_message("error", "OK TO DEL FILE: " . $fullpath);
            } else {
                log_message("error", "FAIL TO DEL FILE: " . $fullpath);
            }
        } else {

        }
    }
}