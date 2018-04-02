<?php

/**
 * @Author: Cleberson Bieleski
 * @Date:   2017-12-23 04:54:45
 * @Last Modified by:   Cleber
 * @Last Modified time: 21-03-2018 11:47:18
 */

namespace util;

class Server{

    /**
     * Verifica se a página está sendo servidor por SSL ou não
     *
     * @return boolean
     */
    public static function isHttps($trust_proxy_headers = false){
        // Verifique o cabeçalho HTTPS padrão
        if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
           return isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off';
        }
        // Verifique os cabeçalhos de proxy, se permitido
        return $trust_proxy_headers && isset($_SERVER['X-FORWARDED-PROTO']) && $_SERVER['X-FORWARDED-PROTO'] == 'https';
        // Padrão para não SSL
        return false;
    }


}