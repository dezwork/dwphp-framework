<?php


// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Yaml\Yaml;

require_once PATH_ROOT."/vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(PATH_ROOT."/app/dev/entity/"), $isDevMode);

// database configuration parameters

if(file_exists(PATH_ROOT.'/app/app_config.yml')){
	$conf_db = Yaml::parse(file_get_contents(PATH_ROOT.'/app/app_config.yml'));

	$conn = array('unix_socket' => '/var/run/mysqld/mysqld.sock');
	$conn['driver']   = $conf_db['default']['db_development']['drive'];
	$conn['host']     = $conf_db['default']['db_development']['host'];
	$conn['user']     = $conf_db['default']['db_development']['username'];
	$conn['password'] = $conf_db['default']['db_development']['password'];
	$conn['dbname']   = $conf_db['default']['db_development']['database'];


	// obtaining the entity manager
	$entityManager = EntityManager::create($conn, $config);
}else{
	echo "app/app_config.yml não encontrado\n";
}