<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 25/12/2017
 * Time: 22:11
 */

namespace Hcode\Util;


use Hcode\DAO\CategoryDAO;

class PagingManager
{
    public static function pageCategoryProducts($page, $idcategory, $itensPerPage = 3)
    {
        $numberPage = (isset($page)) ? $page : 1;
        $start = ($numberPage - 1) * $itensPerPage;
        $limitResult = (new CategoryDAO())->limitProductsSelect($idcategory, $start, $itensPerPage);
        $numberOfPages = ceil($limitResult['total'] / $itensPerPage);
        $pages = [
            'products' => $limitResult['data'],
            'data' => []
        ];

        for ($i = 1; $i <= $numberOfPages; $i++) {
            array_push($pages['data'], [
                'link' => '/categories/' . $idcategory . '?page=' . $i,
                'number' => $i
            ]
            );
        }


        return $pages;
    }
}