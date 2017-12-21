<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 20/12/2017
 * Time: 11:28
 */

namespace Hcode\Util\Files;


class FileUploader
{
    public static function imageProduct($fileImage, $newName)
    {

        $dist = $_SERVER['DOCUMENT_ROOT']
            . DIRECTORY_SEPARATOR . "res"
            . DIRECTORY_SEPARATOR . "site"
            . DIRECTORY_SEPARATOR . "img"
            . DIRECTORY_SEPARATOR . "products"
            . DIRECTORY_SEPARATOR . $newName . ".jpg";

        $arrayFile = explode('.', $fileImage['name']);
        $extension = end($arrayFile);

        switch ($extension) {
            case "jpg":
            case "jpeg":
                $image = imagecreatefromjpeg($fileImage['tmp_name']);
                break;
            case "png":
                $image = imagecreatefrompng($fileImage['tmp_name']);
                break;
            case "gif":
                $image = imagecreatefromgif($fileImage['tmp_name']);
                break;
        }

        imagejpeg($image, $dist);

        imagedestroy($image);

    }

}