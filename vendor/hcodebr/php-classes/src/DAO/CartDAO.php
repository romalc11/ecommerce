<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 26/12/2017
 * Time: 13:11
 */

namespace Hcode\DAO;


use Hcode\Factory\CartFactory;
use Hcode\Factory\ProductFactory;
use Hcode\Model\Cart;

class CartDAO extends DAO
{

    public function save($data = array()): ?Cart
    {
        $default = ["idcart" => null, "dessessionid" => null, "iduser" => null, "deszipcode" => null, "vlfreight" => null, "nrdays" => null];

        $data = array_merge($default, $data);

        $results = $this->select('CALL sp_carts_save(:idcart, :dessessionid, :iduser, :deszipcode, :vlfreight, :nrdays)', $this->formatParameters($data));
        if (count($results) > 0) {

            return CartFactory::create($results[0]);
        }
        return NULL;
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function getBySessionId(): ?Cart
    {
        $results = $this->select('SELECT * FROM tb_carts WHERE dessessionid = :dessessionid', ['dessessionid' => session_id()]);
        if (count($results) > 0) {
            return CartFactory::create($results[0]);
        }
        return NULL;
    }

    public function getById($id): ?Cart
    {
        $results = $this->select('SELECT * FROM tb_carts WHERE idcart = :idcart', [':idcart' => $id]);
        if (count($results) > 0) {
            return CartFactory::create($results[0]);
        }

        return NULL;
    }

    public function selectAll()
    {
        // TODO: Implement selectAll() method.
    }

    public function getProducts($idcart)
    {
        $results = $this->select("SELECT b.idproduct, b.desproduct, b.vlprice, b.vllength, b.vlheight, b.vlweight, b.vlwidth, b.desurl,   COUNT(*) AS nrqtd, SUM(b.vlprice) AS vltotal
                                FROM tb_cartsproducts a 
                                INNER JOIN tb_products b 
                                ON a.idproduct = b.idproduct 
                                WHERE a.idcart = :idcart AND a.dtremoved IS NULL 
                                GROUP BY b.idproduct, b.desproduct, b.vlprice, b.vllength, b.vlheight, b.vlweight, b.vlwidth, b.desurl
                                ORDER BY b.desproduct",

            [
                ':idcart' => $idcart
            ]
        );

        if (count($results) > 0) {
            return ProductFactory::prepareListForCart($results);
        }
        return array();
    }

    public function addProduct($idcart, $idproduct)
    {
        $this->query("INSERT INTO tb_cartsproducts (idcart, idproduct) VALUES (:idcart, :idproduct)",
            [
                ':idcart' => $idcart,
                ':idproduct' => $idproduct
            ]
        );
    }

    public function removeProduct($idcart, $idproduct, $all = true)
    {
        if ($all) {
            $this->query("UPDATE tb_cartsproducts SET dtremoved = NOW() WHERE idcart = :idcart AND idproduct = :idproduct AND dtremoved IS NULL", [
                    ':idcart' => $idcart,
                    ':idproduct' => $idproduct
                ]
            );
        } else {
            $this->query("UPDATE tb_cartsproducts SET dtremoved = NOW() WHERE idcart = :idcart AND idproduct = :idproduct AND dtremoved IS NULL LIMIT 1", [
                    ':idcart' => $idcart,
                    ':idproduct' => $idproduct
                ]
            );
        }

    }
}