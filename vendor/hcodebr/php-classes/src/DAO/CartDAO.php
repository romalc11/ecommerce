<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 26/12/2017
 * Time: 13:11
 */

namespace Hcode\DAO;


use Hcode\Factory\CartFactory;
use Hcode\Model\Cart;

class CartDAO extends DAO
{

    public function save($data = array()) : ?Cart
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

    public function getBySessionId() : ?Cart
    {
        $results = $this->select('SELECT * FROM tb_carts WHERE dessessionid = :dessessionid', ['dessessionid' => session_id()]);
        if (count($results) > 0) {
            return CartFactory::create($results[0]);
        }
        return NULL;
    }

    public function getById($id) : ?Cart
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
}