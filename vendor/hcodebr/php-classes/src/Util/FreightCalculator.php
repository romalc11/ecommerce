<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 30/12/2017
 * Time: 00:22
 */

namespace Hcode\Util;


use Hcode\DAO\CartDAO;
use Hcode\Factory\CartFactory;

class FreightCalculator
{
    public static function calculateFor($nrzipcode)
    {
        $nrzipcode = str_replace('-', '', $nrzipcode);
        $cart = CartFactory::createBySession();
        $cartDAO = new CartDAO();
        $cartFreteInfo = $cartDAO->getCartProductsInfo($cart->getIdcart());
        if ($cartFreteInfo['vlheight'] < 2) $cartFreteInfo['vllength'] = 2;
        if ($cartFreteInfo['vllength'] < 16) $cartFreteInfo['vllength'] = 16;
        if ($cartFreteInfo['vlwidth'] + $cartFreteInfo['vllength'] + $cartFreteInfo['vlheight'] > 200) $cartFreteInfo['vlwidth'] = 200 - $cartFreteInfo['vllength'] - $cartFreteInfo['vlheight'];
        if ($cartFreteInfo['vlprice'] > 10000) $cartFreteInfo['vlprice'] = 10000;

        if ($cartFreteInfo['nrqtd'] > 0) {
            $qs = http_build_query([
                'nCdEmpresa' => '',
                'sDsSenha' => '',
                'nCdServico' => '40010',
                'sCepOrigem' => '09853120',
                'sCepDestino' => $nrzipcode,
                'nVlPeso' => $cartFreteInfo['vlweight'],
                'nCdFormato' => '1',
                'nVlComprimento' => $cartFreteInfo['vllength'],
                'nVlAltura' => $cartFreteInfo['vlheight'],
                'nVlLargura' => $cartFreteInfo['vlwidth'],
                'nVlDiametro' => '0',
                'sCdMaoPropria' => 'S',
                'nVlValorDeclarado' => $cartFreteInfo['vlprice'],
                'sCdAvisoRecebimento' => 'S'
            ]
            );

            $xml = simplexml_load_file('http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPrecoPrazo?' . $qs);
            $result = $xml->Servicos->cServico;

            if ($result->MsgErro != '') {
                throw new \Exception($result->MsgErro);
            }
            $cart->setNrdays((int)$result->PrazoEntrega);
            $cart->setDeszipcode($nrzipcode);
            $cart->setVlfreight(formatStringValueToDecimal($result->Valor));

            $cartDAO->save($cart->getValuesColumnTable());
        }
    }

}