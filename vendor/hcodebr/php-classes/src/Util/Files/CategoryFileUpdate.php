<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 17/12/2017
 * Time: 20:51
 */

namespace Hcode\Util\Files;


class CategoryFileUpdate extends FileUpdate
{
    public function __construct()
    {
        $root = $_SERVER["DOCUMENT_ROOT"]. DIRECTORY_SEPARATOR ;
        $this->files = [
            $root. "views" . DIRECTORY_SEPARATOR . "categories-menu.html" => function($categories){
                $file = [];
                foreach ($categories as $row){
                    array_push($file, '<li><a href="/categories/' . $row['idcategory'] . '">'.$row['descategory'] .'</a></li>');
                }
                return $file;
            }
        ];
    }
}