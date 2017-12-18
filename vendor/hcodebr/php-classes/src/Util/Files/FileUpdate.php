<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 17/12/2017
 * Time: 20:46
 */

namespace Hcode\Util\Files;

abstract class FileUpdate
{
    protected $files;

    public function updateBy($list)
    {
        foreach ($this->files as $key => $fileFunction) {
            $file = $fileFunction($list);
            file_put_contents($key, implode('', $file));
        }
    }

}