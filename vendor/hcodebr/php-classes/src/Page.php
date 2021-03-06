<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 08/12/2017
 * Time: 14:18
 */

namespace Hcode;

use Rain\Tpl;

class Page
{

    private $tpl;
    private $options = [];

    public function __construct($opts = array(), $tpl_dir = "/views/")
    {
        $this->options = $opts;

        $config = array(
            "tpl_dir" => $_SERVER["DOCUMENT_ROOT"] . $tpl_dir,
            "cache_dir" => $_SERVER["DOCUMENT_ROOT"] . "/views-cache/",
            "debug" => false
        );

        Tpl::configure($config);
        $this->tpl = new Tpl;

        $this->setData($this->options["data"]);

        if ($this->options["header"]) $this->tpl->draw("header");
    }

    public function __destruct()
    {
        if ($this->options["footer"]) $this->tpl->draw("footer");
    }

    public function setTpl($name, $returnHTML = false)
    {
        return $this->tpl->draw($name, $returnHTML);
    }

    private function setData($data = array()): void
    {
        foreach ($data as $key => $value) {
            $this->tpl->assign($key, $value);
        };
    }
}