<?php

/**
 * @Author: Cleberson Bieleski
 * @Date:   2017-12-23 04:54:45
 * @Last Modified by:   Cleberson Bieleski
 * @Last Modified time: 2017-12-31 11:06:27
 */

if(!defined('PATH_ROOT')){
	define('PATH_ROOT', dirname(__FILE__) );
}

require_once PATH_ROOT."/dwphp/config/bin/doctrine/bootstrap.php";


return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);