<?php

class composerAutoExecute {
	private $terminal;
	private $folderTools;
	private $directory;
	private $hasComposerPhar;
	private $characterForLine = 100;

	public function __construct(){

		$this->setFolderTools(PATH_ROOT."dwphp/config/bin/composer");
		chdir(PATH_ROOT);
		if(file_exists('../vendor/autoload.php')){
			$this->setDirectory("../");
		}else if (file_exists('../../vendor/autoload.php')){
			chdir(PATH_ROOT."/public");
		}else{
			$this->setDirectory("");
		}
		if(file_exists($this->getDirectory().'composer.phar')){
			$this->setHasComposerPhar(true);
		}else{
			$this->setHasComposerPhar(false);
		}
		$this->process();
	}

	public function process(){

		if(php_sapi_name() != "cli") {
	       $this->setTerminal(false);
	    }else{
	    	header('Content-Type:text/plain');
	    	$this->setTerminal(true);
	    }


		putenv("COMPOSER_HOME=".getcwd());
		if($this->getTerminal()==false){
			echo "
			<style>
				body{ background: #333;	text-align: center; padding-top: 30px; }
				*{font-family: monospace; color: #FFF; font-weight: 100; font-size: 14px; }
				.y{ color: #9acd32;}
				.n{ color: #a52a2a;}
			</style>"."\n";
		}

		if($this->getHasComposerPhar()==false){
			if(!file_exists(PATH_ROOT."/dwphp/config/bin/composer/composer.phar")){

				$this->directoryComposer();

				//exibe mensagem de prepado
				$message	=	"Preparando o download do composer.phar ";
				$this->showMensage($message);

				//inicia o donwload
				$this->downloadComposer();

				if(file_exists(PATH_ROOT."/dwphp/config/bin/composer/composer.phar")){
					$this->showMensage(str_pad("> OK", ($this->getCharacterForLine() - strlen(utf8_decode($message))), ".", STR_PAD_LEFT));
				}else{
					$this->showMensage(str_pad("> ERRO", ($this->getCharacterForLine() - strlen(utf8_decode($message))), ".", STR_PAD_LEFT));
				}
			}else{
				$message	=	"Conferindo arquivo composer.phar ";
				$this->showMensage($message);
				$this->showMensage(str_pad("> OK", ($this->getCharacterForLine() - strlen(utf8_decode($message))), ".", STR_PAD_LEFT));
			}
		}

		chdir(PATH_ROOT);
		if($this->getHasComposerPhar()==false){
			$message	=	"Copiando composer.phar para a raiz ";
			$this->showMensage($message);

			if(copy(PATH_ROOT."/dwphp/config/bin/composer/composer.phar", $this->getDirectory().'composer.phar')){
				$this->showMensage(str_pad("> OK", ($this->getCharacterForLine() - strlen(utf8_decode($message))), ".", STR_PAD_LEFT));
			}else{
				$this->showMensage(str_pad("> ERRO", ($this->getCharacterForLine() - strlen(utf8_decode($message))), ".", STR_PAD_LEFT));
			}
		}

		//executando scritp
		if($this->getTerminal()==true){

			$message = (file_exists('vendor')? "Realizando atualização de dependências ": "Realizando instalação de dependências ");
			$this->showMensage($message);
			$this->showMensage(str_pad("> OK", ($this->getCharacterForLine() - strlen(utf8_decode($message))), ".", STR_PAD_LEFT));
			$message='';


			if(defined('ENVIRONMENT') && ENVIRONMENT!='development' && ENVIRONMENT!='testing'){
				$this->executar("php composer.phar update --no-dev");
			}else{
				$this->executar("php composer.phar update");
			}

		}else{

			$message = (file_exists('vendor')? "Realizando atualização de dependências ": "Realizando instalação de dependências ");
			$this->showMensage($message);
			if(defined('ENVIRONMENT') && ENVIRONMENT!='development' && ENVIRONMENT!='testing'){
				$this->executar("php composer.phar update --no-dev 2>&1");
			}else{
				$this->executar("php composer.phar update 2>&1");
			}
		}

		$this->showMensage(str_pad((file_exists('vendor')?"> OK":"> ERRO"), ($this->getCharacterForLine() - strlen(utf8_decode($message))), ".", STR_PAD_LEFT));


		$message	=	"Removendo arquivos temporários ";
		$this->showMensage($message);

		if(file_exists("cache")){ $this->executar(" rm -rf cache"); }
		if(file_exists("keys.dev.pub")){ $this->executar(" rm keys.dev.pub"); }
		if(file_exists("keys.tags.pub")){ $this->executar(" rm keys.tags.pub"); }
		if(file_exists("composer.phar")){ $this->executar(" rm composer.phar"); }

		if(!file_exists("cache") && !file_exists("keys.dev.pub") && !file_exists("keys.tags.pub") && !file_exists("composer.phar")){
			$this->showMensage(str_pad("> OK", ($this->getCharacterForLine() - strlen(utf8_decode($message))), ".", STR_PAD_LEFT));
		}else{
			$this->showMensage(str_pad("> ERRO", ($this->getCharacterForLine() - strlen(utf8_decode($message))), ".", STR_PAD_LEFT));
		}


		$message	=	"";
		for ($i=0; $i < 3; $i++) {
			$message .= '<br/>.';
		}
		$this->showMensage($message);

		if($this->getTerminal()==true){
			$message	=	"Concluído - OK";
			$this->showMensage($message,2);
		}else{
			$message	=	"<br/><br/>Aguardando redirecionamento... ";
			$this->showMensage($message,2);

			$this->showMensage("<script>location.href = './';</script>");
		}

		/*
		$percent = 0;
		for ($i = 0; $i <= 50; $i++) {
		    echo "Processing (".$percent."%)" . "\r";
		    sleep(1);
		    $percent++;
		    $percent++;
		}
		*/

	}
	public function downloadComposer(){
		chdir(PATH_ROOT.'/dwphp/config/bin/composer/');
		$this->executar('php -r "readfile(\'https://getcomposer.org/installer\');" | php');
	}
	public function directoryComposer(){
		if(!file_exists(PATH_ROOT."/dwphp/config/bin/composer/")){
			$message	=	"Criando diretório para baixar composer.phar ";
			$this->showMensage($message);
			// Criando pasta
			mkdir(PATH_ROOT."/dwphp/config/bin/composer/", 0777, true);
			// Ferifica processo
			if(file_exists(PATH_ROOT."/dwphp/config/bin/composer/")){
				$this->showMensage(str_pad("> OK", ($this->getCharacterForLine() - strlen(utf8_decode($message))), ".", STR_PAD_LEFT));
			}else{
				$this->showMensage(str_pad("> ERRO", ($this->getCharacterForLine() - strlen(utf8_decode($message))), ".", STR_PAD_LEFT));
			}


			// Comçando mensagem para usuário
			$message	=	"Definindo permissão da pasta ";
			$this->showMensage($message);
			// Definindo pemissão
			chmod(PATH_ROOT."/dwphp/config/bin/composer/", 0777);
			// Ferifica processo
			if(is_writable(PATH_ROOT."/dwphp/config/bin/composer/")){
				$this->showMensage(str_pad("> OK", ($this->getCharacterForLine() - strlen(utf8_decode($message))), ".", STR_PAD_LEFT));
			}else{
				$this->showMensage(str_pad("> ERRO", ($this->getCharacterForLine() - strlen(utf8_decode($message))), ".", STR_PAD_LEFT));
			}

		}
	}
	public function setFolderTools($v){
		$this->folderTools = $v;
	}
	public function getFolderTools(){
		return $this->folderTools;
	}
	public function setDirectory($v){
		$this->directory = $v;
	}
	public function getDirectory(){
		return $this->directory;
	}
	public function setHasComposerPhar($v){
		$this->hasComposerPhar = $v;
	}
	public function getHasComposerPhar(){
		return $this->hasComposerPhar;
	}
	public function setCharacterForLine($v){
		$this->characterForLine = $v;
	}
	public function getCharacterForLine(){
		return $this->characterForLine;
	}
	public function setTerminal($v){
		$this->terminal = $v;
	}
	public function getTerminal(){
		return $this->terminal;
	}
	public function showMensage($m,$time=0){
		if($this->getTerminal()==false){
			if($time!=0){
				sleep($time);
			}
		}
		if($this->getTerminal()==true){
			$m=str_replace("<br/>.", "\n", $m);
			if(strpos($m, "OK")!==false){
				$m = "\e[32m".$m."\e[0m \n";
			}else if(strpos($m, "ERRO")!==false){
				$m = "\e[31m".$m."\e[0m \n";
			}
		}else{
			if(strpos($m, "OK")!==false){
				$m = "<b class='y'>".$m."</b><br/><br/>";
			}else if(strpos($m, "ERRO")!==false){
				$m = "<b class='n'>".$m."</b><br/><br/>";
			}
		}
		echo $m;
		if($this->getTerminal()==false){ob_flush();}
		if($this->getTerminal()==false){flush();}

	}
	public function executar($code=''){
		if($code!=''){
			exec($code);
		}

	}
}

new composerAutoExecute(); exit();