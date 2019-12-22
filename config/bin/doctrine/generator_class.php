<?php

use Symfony\Component\Yaml\Yaml;
//vamos configurar a chamada ao Entity Manager, o mais importante do Doctrine

// o Autoload é responsável por carregar as classes sem necessidade de incluí-las previamente
date_default_timezone_set('America/Sao_Paulo');

if(!defined('PATH_ROOT')){
	define('PATH_ROOT', str_replace('/vendor/dezwork/dwphp-framework/config/bin/doctrine','',dirname(__FILE__)) );
}

$dir = PATH_ROOT."/app/entity/";

require_once PATH_ROOT."/vendor/autoload.php";

// o Doctrine utiliza namespaces em sua estrutura, por isto estes uses
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

//onde irão ficar as entidades do projeto? Defina o caminho aqui
$isDevMode = true;

// configurações de conexão. Coloque aqui os seus dados
if(file_exists(PATH_ROOT.'/app/app_config.yml')){
	$conf_db = Yaml::parse(file_get_contents(PATH_ROOT.'/app/app_config.yml'));

	$conn = array('unix_socket' => '/var/run/mysqld/mysqld.sock');
	$conn['driver']   = $conf_db['default']['db_development']['drive'];
	$conn['host']     = $conf_db['default']['db_development']['host'];
	$conn['user']     = $conf_db['default']['db_development']['username'];
	$conn['password'] = $conf_db['default']['db_development']['password'];
	$conn['dbname']   = $conf_db['default']['db_development']['database'];

}else{
	echo "app/app_config.yml não encontrado\n";
}


if (is_dir($dir)) {

    $iterator = new \FilesystemIterator($dir);

    if ($iterator->valid()) {

        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ( $ri as $file ) {

            $file->isDir() ?  rmdir($file) : unlink($file);
        }
    }
}
//setando as configurações definidas anteriormente
$config = Setup::createAnnotationMetadataConfiguration(array($dir), $isDevMode);
//criando o Entity Manager com base nas configurações de dev e banco de dados
$em = EntityManager::create($conn, $config);
$em->getConfiguration()->setMetadataDriverImpl(
    new \Doctrine\ORM\Mapping\Driver\DatabaseDriver(
        $em->getConnection()->getSchemaManager()
    )
);
$cmf = new \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory();
$cmf->setEntityManager($em);
$metadata = $cmf->getAllMetadata();

$generator = new \Doctrine\ORM\Tools\EntityGenerator();
$generator->setUpdateEntityIfExists(true);
$generator->setGenerateStubMethods(true);
$generator->setGenerateAnnotations(true);
$generator->generate($metadata, $dir);



	$assignature="<?php
namespace App\Entity;
use DwPhp\Library\models\AbstractObject;\n";

	$addClass="}\n\n    public function getNameTable(){
        return '@NameTable';
    } \n}";

	$procurar = array("@ORM\\","private",")\n    {","\n{","<?php\n\n\n\n","}\n}");
	$colocar = array("@","protected","){","{",$assignature,$addClass);

    $types = array( 'php');
	$path = new DirectoryIterator($dir);
	$contador=0;
	foreach ($path as $fileInfo) {
	    $ext = strtolower( $fileInfo->getExtension() );
	    if( in_array( $ext, $types ) ){

	    	$arquivo = $dir.$fileInfo->getFilename();
	    	$ponteiro = fopen ($arquivo,"r");

			//LÊ O ARQUIVO ATÉ CHEGAR AO FIM
			while (!feof ($ponteiro)) {
			  $linha = fgets($ponteiro,4096);
			  $tmpLine=$linha;

			  if(strpos($tmpLine,'(\\')!==false){
			  	$tmp = explode("(",$tmpLine);
			  	$tmp = explode("$",$tmp[1]);

			  	if($n=array_search($tmp[0],$procurar)){
			  		$procurar[$n] = $tmp[0];
			  		$colocar[$n] = "";
			  	}else{
				  	$procurar[] = $tmp[0];
				  	$colocar[] = "";
				}

			  }


			  if(strpos($tmpLine,'* @ORM\Table(name="')!==false){
			  	$nametable = explode('"', $tmpLine);
			  	if($p=array_search('@NameTable',$procurar)){
			  		$procurar[$p] = '@NameTable';
			  		$colocar[$p] = $nametable[1];
			  	}else{
				  	$procurar[] = '@NameTable';
				  	$colocar[] = $nametable[1];
				}
			  }

			}

			//Obtem o conteudo do arquivo
			$obter = file_get_contents($arquivo);
			$novo = str_replace($procurar, $colocar, $obter);

			$namClass=str_replace(".php", "", $fileInfo->getFilename());
			if(strpos($novo,$namClass)!==false){
				$novo = str_replace($namClass."{", $namClass." extends AbstractObject{", $novo);
			}

			//Grava o novo texto (modificado) no arquivo
			$gravar = fopen($arquivo, "w");
			fwrite($gravar, $novo);
			fclose($gravar);
			$contador++;

	    }
	}
	if($contador>0){
		echo "\n\nGerou ".($contador)." novas classes em '".$dir."'\n";
	}else{
		echo "Nenhuma classe gerada\n";
	}
