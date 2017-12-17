<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 15/12/2017
 * Time: 21:36
 */

namespace Hcode\Builder;


use Hcode\Page;
use Hcode\PageAdmin;

class PageBuilder
{
    private $tpl;
    private $opt = [
        'header' => false,
        'footer' => false,
        'data' => []
    ];

    public function withHeader() : PageBuilder
    {
        $this->opt['header'] = true;
        return $this;
    }

    public function withFooter() : PageBuilder
    {
        $this->opt['footer'] = true;
        return $this;
    }

    public function withData($data = []) : PageBuilder
    {
        $this->opt['data'] = $data;
        return $this;
    }

    public function withTpl($name) : PageBuilder
    {
        $this->tpl = $name;
        return $this;
    }

    public function build($admin = false)
    {
            if($admin){
                $page = new PageAdmin($this->opt);
            } else {
                $page = new Page($this->opt);
            }

            $page->setTpl($this->tpl);
    }
}