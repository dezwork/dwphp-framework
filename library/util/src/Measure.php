<?php

/**
 * @Author: Cleberson Bieleski
 * @Date:   2017-12-23 04:54:45
 * @Last Modified by:   Cleber
 * @Last Modified time: 21-03-2018 11:35:23
 */

namespace util;

class Measure{

    /**
     * Formatação agradável para tamanhos de computador (Bytes).
     *
     * @param   integer $bytes    O número em bytes para formatar
     * @param   integer $decimals O número de pontos decimais a incluir
     * @return  string
     */
    public static function sizeFormat($bytes, $decimals = 0){
        $bytes = floatval($bytes);
        if ($bytes < 1024) {
            return number_format($bytes, $decimals, '.', '') . ' B';
        } elseif ($bytes < pow(1024, 2)) {
            return number_format($bytes / 1024, $decimals, '.', '') . ' KB';
        } elseif ($bytes < pow(1024, 3)) {
            return number_format($bytes / pow(1024, 2), $decimals, '.', '') . ' MB';
        } elseif ($bytes < pow(1024, 4)) {
            return number_format($bytes / pow(1024, 3), $decimals, '.', '') . ' GB';
        } elseif ($bytes < pow(1024, 5)) {
            return number_format($bytes / pow(1024, 4), $decimals, '.', '') . ' TB';
        } elseif ($bytes < pow(1024, 6)) {
            return number_format($bytes / pow(1024, 5), $decimals, '.', '') . ' PB';
        } else {
            return number_format($bytes / pow(1024, 5), $decimals, '.', '') . ' PB';
        }
    }

    /**
     * Formatação agradável para tamanhos de computador (Bytes).
     *
     * @param   integer $bytes    O número com medida ex: 1T
     * @param   integer $decimals O tipo de saída
     * @return  string
     */
    function convertMemorySize($strval, string $to_unit = 'b'){
        $strval    = strtolower(str_replace(' ', '', $strval));
        $val       = floatval($strval);
        $to_unit   = strtolower(trim($to_unit))[0];
        $from_unit = str_replace($val, '', $strval);
        $from_unit = empty($from_unit) ? 'b' : trim($from_unit)[0];
        $units     = 'kmgtph';  // (k)ilobyte, (m)egabyte, (g)igabyte and so on...

        // Convert to bytes
        if ($from_unit !== 'b'){
            $val *= 1024 ** (strpos($units, $from_unit) + 1);
        }

        // Convert to unit
        if ($to_unit !== 'b'){
            $val /= 1024 ** (strpos($units, $to_unit) + 1);
        }

        $val = $val.' '.($to_unit!='b'?strtoupper($to_unit).'B':'B');

        return $val;
    }


}