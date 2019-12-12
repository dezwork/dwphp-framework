<?php


if(!defined('PATH_ROOT')){
	define('PATH_ROOT', dirname(__FILE__) );
}


if(file_exists(PATH_ROOT."/vendor/dezwork/dwphp-framework/config/bin/doctrine/bootstrap.php")){
	require_once PATH_ROOT."/vendor/dezwork/dwphp-framework/config/bin/doctrine/bootstrap.php";
}else{
	echo "error on cli-config.php"; exit();
}


return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);