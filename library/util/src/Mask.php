<?php

/**
 * @Author: Cleberson Bieleski
 * @Date:   2017-12-23 04:54:45
 * @Last Modified by:   Cleber
 * @Last Modified time: 2018-03-19 17:36:47
 */

namespace util;


class Mask{

    const TELEFONE = '8 OU 9 DIGITOS';
    const DOCUMENTO = 'CPF OU CNPJ';
    const CPF = '###.###.###-##';
    const CNPJ = '##.###.###/####-##';
    const CEP = '##.###-###';
    const MAC = '##:##:##:##:##:##';

    /**
     * Adiciona máscara em um texto
     *
     * @param  string   $txt Texto
     * @param  Mask     $mascara
     * @return string (Texto com mascara)
     */
    public static function mask($txt='', $mascara='') {
        if(empty($txt) || empty($mascara)){
            return false;
        }else if($mascara == Mask::TELEFONE){
            $mascara =  (strlen($txt) == 10 ? '(##)####-####' : '(##)#####-####');
        }else  if($mascara == Mask::DOCUMENTO){
            $mascara = (strlen($txt) == 14 ? Mask::CNPJ : (strlen($txt) == 11? Mask::CPF : ''));
        }

        return Mask::MaskFactory($txt , $mascara);


    }

    /**
     * Adiciona máscara
     *
     * @param  string $texto
     * @return string (Texto com a mascara)
    */
    public static function maskFactory($txt='', $mascara='') {
        $txt = Mask::unmask($txt);
        if(empty($txt) || empty($mascara)){
            return false;
        }

        $qtd = substr_count($mascara, '#');
        if($qtd != strlen($txt) && strlen($txt)!=0) {
            return false;
        }else{
            $string = str_replace(" ", "", $txt);
            for ($i = 0; $i < strlen($string); $i++) {
                $pos = strpos($mascara, "#");
                $mascara[$pos] = $string[$i];
            }
            return $mascara;
        }
    }


    /**
     * Remove máscara de um texto
     *
     * @param  string $texto
     * @return string (Texto sem a mascara)
     */
    public static function unMask($texto) {
        return preg_replace('/[\-\|\(\)\/\.\: ]/', '', $texto);
    }

}