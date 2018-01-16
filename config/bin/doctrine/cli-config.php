<?php

/**
 * @Author: Cleberson Bieleski
 * @Date:   2017-12-23 04:54:45
 * @Last Modified by:   Cleber
 * @Last Modified time: 2018-01-16 19:17:20
 */

if(!defined('PATH_ROOT')){
	define('PATH_ROOT', dirname(__FILE__) );
}


if(file_exists(PATH_ROOT."/vendor/dezwork/dwphp-framework/config/bin/doctrine/bootstrap.php")){
	require_once PATH_ROOT."/vendor/dezwork/dwphp-framework/config/bin/doctrine/bootstrap.php";
}else{
	echo "error on cli-config.php"; exit();
}


return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);