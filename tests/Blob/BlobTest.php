<?php

use \Tsugi\Util\U;
use \Tsugi\Blob\BlobUtil;

class BlobTest extends PHPUnit_Framework_TestCase
{
    // https://stackoverflow.com/questions/11267086/php-unlink-all-files-within-a-directory-and-then-deleting-that-directory
    public static function recursiveRemoveDirectory($directory)
    {
        // echo("\n");
        if ( ! file_exists($directory) ) return;
        foreach(glob("{$directory}/*") as $file)
        {
            if(is_dir($file)) {
                if ( ! preg_match('/^[0-9][0-9][0-9]/', basename($file)) ){
                    echo("Skipping folder $file\n");
                    continue;
                }
                // echo("Recurse folder $file\n");
                self::recursiveRemoveDirectory($file);
            } else {
                if ( ! preg_match('/^[0-9a-f]+$/', basename($file)) ){
                    echo("Skipping file $file\n");
                    continue;
                }
                // echo("Delete file $file\n");
                unlink($file);
            }
        }
        // echo("Remove directory $directory\n");
        rmdir($directory);
    }

    public function testGet() {
        $tmp = sys_get_temp_dir();
        if (strlen($tmp) > 1 && substr($tmp, -1) == '/') $tmp = substr($tmp,0,-1);
        $tmp .= '/tsugi_unit';
        if ( strlen($tmp) < 10 ) {
            die('Dangerous to delete '.$tmp);
        }
        self::recursiveRemoveDirectory($tmp);
        mkdir($tmp);
        $blob = BlobUtil::getBlobFolder(42, $tmp);
        $this->assertTrue(strpos($blob, 'tsugi_unit/000/042/00000042') > 0);
        self::recursiveRemoveDirectory($tmp);
    }

}
