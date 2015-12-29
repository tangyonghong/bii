<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Bii\Core\Import;
/**
 * Description of import
 * @author tangyonghong <tangyonghong@kugou.net>
 * @copyright (c) year, Tangyonghong
 */
class Import {

    public static function loadFile($filePath) {
        if (!file_exists($filePath)) { //文件不存在 
            return false;
        }

        if (is_dir($filePath)) { //如果是目录
            $lastFileSplit = substr(strrev($filePath), 0, 1); //判断文件最后是否有分割符
            if (!in_array($lastFileSplit, array("/", "\\"))) { //如果最后的分割符 是/或 \
                $filePath.="/";
            }

            $handler = opendir($filePath);
            while (($file = readdir($handler)) !== false) {
                if (!in_array($file, array(".", ".."))) {
                    if (is_dir($filePath . $file)) {
                        self::loadFile($filePath . $file);
                    } else {
                        include_once  $filePath . $file;
                    }
                }
            }
            closedir($handler);
        } else { //为文件则直接include
            include_once $filePath;
        }
        return true;
    }

}

?>
