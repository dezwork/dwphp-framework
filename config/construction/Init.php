<?php

/**
 * @Author: Cleberson Bieleski
 * @Date:   2017-12-23 04:54:45
 * @Last Modified by:   Cleber
 * @Last Modified time: 29-06-2018 14:18:46
 */

namespace DwPhp;
use Symfony\Component\Yaml\Yaml;
use Monolog\Logger;
use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

/**
  Class for initialize.
  Essa classe tem como principio definir todas as variaveis e paths responsaveis pelo funcionamento do framework.
 */
class Init{
	//variavel que determina se está desenvolvimento local, padrão true
	//nome do projeto
	public $projectName 	    =	'dwphp';
	//production, staging, testing ou development
	public $environmentStatus 	=	'';
	//array com todas as aplicações ativas no sistmas, por padrão já inicializa com default
	public $application 		=	array();
	//local onde será encontrado os arquivos da application
	private $applicationPath 	=	'';
	//local onde será encontrado os arquivos da application
	private $applicationName 	=	'';
	//local do arquivo controller ou view
	private $localFileRead 		=	'';
	//local onde será encontrado os arquivos do public
	private $publicPath 		=	'';
	//nome da applicação em uso no momento
	private $nameApplication 	=	'';
	/**
		define variaveis para alterar as configurações do php.ini
	*/
	// exibe ou não os erros no browser. Opções (On | Off)
	private $displayErrors 		= 	'On';
	// exibe ou não os erros no browser. Opções (On | Off)
	private $logErrors 			= 	'On';
	// local onde os logs de erros devem ser salvos no sistema. Padrão PATH_ROOT.'/data/log/error_system.log'
	private $errorLog 			=	'/storage/log/error_php.log';
	// local onde os logs de erros devem ser salvos no sistema. Padrão PATH_ROOT.'/data/log/error_system.log'
	private $errorSql 			=	'/storage/log/error_sql.log';
	// define a diretiva em tempo de execução. O PHP tem vários níveis de erros, usando esta função você pode definir o nível durante a execução do seu script.
	private $errorReporting 	=	"0";
	// define quantos registros devem ser salvos sobre o carregamantos das páginas;
	private $limitDataLoadPage 	=	100;
	/**
		define configurações do cache
	*/
	// Define o limitador de cache para 'private' */
	/* 	nochace: 			Rejeitaria qualquer armazenamento no cache do cliente.
		private: 			Um pouco mais restritivo do que public.
		private_no_expire: 	Header expirado nunca é enviado para o cliente nesse modo.
		public: 			Permitiria o armazenamento no cache
	*/

	private $cacheLimiter 		=	'private';
	// local onde os arquivos temporarios das sessions serão salvas. Padrão PATH_ROOT.'/data/cache/'
	private $sessionSavePath 	= 	'/storage/cache/session/';
	/* define o prazo do cache será expirado em segundo. Por padrão, 7 dias */
	private $cacheExpire 		=	10080;
	/* permite cache de páginas. Padrão é false */
	private $chaceNavegation 	= 	false;
	/**
		define localidade, padrão pt_BR
	*/
	private $locale 			=	'pt_BR';
	private $dateTimezone 		=	'America/Sao_Paulo';
	private $uploadMaxFilesize =	'100';
	private $postMaxSize 		=	'100';

	/**
		controle da url
	*/
	// força o direcoinamento para https
	private $useHttps 			=	'Off';
	// força a utilização de www
	private $useWww 			=	'On';
	// endereço web sem http e sem www
	public $addressUri 			=	'';
	// endereço web sem http e sem www
	public $pathBaseHref 		=	'';
	// armazenará as posições da url amigavel: getPosUrl($num), retornará o resultado contando além do path Application
	public $posURL 				= 	array();
	// diretório da url após o nome application ex: dwphp/gerencidor/teste/acb123/ -> retorno: teste/acb123/
	public $urlCompletePath		= 	'';
	/**
		Database para desnevolvimento
	*/
	private $dbConfig 			=	array();

	//controle de arquivos
	public  $pathURI;
	public  $pageCtrl;
	public  $pageAction;
	public  $pageView;
	public  $ctrlFunction;
	public  $methodsURI;

	function __construct($type=''){
		if($type=='test'){
			return false;
		}
		// import variables to configuration of system
		try {
			$this->getImportDataConfiguration();
		}catch(\Exception $e){
			$this->notificationErrors('Falha na função getImportDataConfiguration', $e->getMessage()); exit;
		}

		// Defining configuration of system
		try {
			$this->setSystemConfigs();
		}catch(\Exception $e){
			$this->notificationErrors('Falha na função setSystemConfigs', $e->getMessage()); exit;
		}

		// Defining application path of use
		try {
			$this->getApplicationUseNow();
		}catch(\Exception $e){
			$this->notificationErrors('Falha na função getApplicationUseNow', $e->getMessage()); exit;
		}

		// Defining configuration of system
		try {
			$this->getImportCurrentDataApplication();
		}catch(\Exception $e){
			$this->notificationErrors('Falha na função getImportCurrentDataApplication', $e->getMessage()); exit;
		}

		// Defining configuration of application
		try {
			$this->setSystemConfigsCurrentApplication();
		}catch(\Exception $e){
			$this->notificationErrors('Falha na função setSystemConfigsCurrentApplication', $e->getMessage()); exit;
		}

		//return path into application
		$this->getApplicationPathFiles();
	}

	private function getImportDataConfiguration(){
		// import config geral of system.
		if(file_exists(PATH_ROOT.'/config.yml')){
			try {
				$conf = Yaml::parse(file_get_contents(PATH_ROOT.'/config.yml'));
			}catch(ParseException $e){
			    throw new \Exception("Não é possível analisar dados YAML: %s ".$e->getMessage());
			}
		}else{
			throw new \Exception("config.yml Arquivo de configuração não foi encontrado na raiz do projeto.");
		}

		//verify and define applicatoin disponible for project in addition to default.
		if(isset($conf['application_src']) && !is_array($conf['application_src'])){
			throw new \Exception("config.yml Você precisa definir um array de 'application_src'. Ex: default: /app/default/");
		}else if(array_key_exists('default', $conf['application_src'])==''){
			throw new \Exception("config.yml Você precisa uma aplicação como default. Ex: default: /app/default/");
		}else{
			$this->setApplication($conf['application_src']);
		}


		//Get project name of file config.yml
		if(isset($conf['project_name']) && !empty($conf['project_name'])){
			$this->setProjectName($conf['project_name']);
		}
		//Get display_errors of file config.yml
		if(isset($conf['display_errors'])){
			$this->setDisplayErrors($conf['display_errors']);
		}
		//Get log_errors of file config.yml
		if(isset($conf['log_errors'])){
			$this->setLogErrors($conf['log_errors']);
		}
		//Get error_log of file config.yml
		if(isset($conf['error_log'])){
			$this->setErrorLog($conf['error_log']);
		}
		//Get error_sql of file config.yml
		if(isset($conf['error_sql'])){
			$this->setErrorSql($conf['error_sql']);
		}
		//Get error_reporting of file config.yml
		if(isset($conf['error_reporting'])){
			$this->setErrorReporting($conf['error_reporting']);
		}
		//Get session_cache_limiter of file config.yml
		if(isset($conf['session_cache_limiter'])){
			$this->setCacheLimiter($conf['session_cache_limiter']);
		}
		//Get session_save_path of file config.yml
		if(isset($conf['session_save_path'])){
			$this->setSessionSavePath($conf['session_save_path']);
		}
		//Get session_cache_expire of file config.yml
		if(isset($conf['session_cache_expire'])){
			$this->setCacheExpire($conf['session_cache_expire']);
		}
		//Get locale  of file config.yml
		if(isset($conf['locale'])){
			$this->setLocale($conf['locale']);
		}
		//Get date_timezone of file config.yml
		if(isset($conf['date_timezone'])){
			$this->setDateTimezone($conf['date_timezone']);
		}
		//Get upload_max_filesize of file config.yml
		if(isset($conf['upload_max_filesize'])){
			$this->setUploadMaxFilesize($conf['upload_max_filesize']);
		}
		//Get post_max_size of file config.yml
		if(isset($conf['post_max_size'])){
			$this->setPostMaxSize($conf['post_max_size']);
		}
		//Get cache_navegation of file config.yml
		if(isset($conf['cache_navegation'])){
			$this->setChaceNavegation($conf['cache_navegation']);
		}
		//Get limit_data_load_page of file config.yml
		if(isset($conf['limit_data_load_page'])){
			$this->setLimitDataLoadPage($conf['limit_data_load_page']);
		}

	}

	// configura applications
	public function getImportCurrentDataApplication(){

		$app_config = $this->getAppConfigYaml();

		foreach ($app_config[$this->getApplicationName()] as $key => $value) {
            if(isset($value['address_uri'])){
				$addresUri = explode('/', $value['address_uri']);
                if(!empty($addresUri[0]) && strpos($_SERVER['HTTP_HOST'] , $addresUri[0]) !== false ){
                	$this->setEnvironmentStatus($key);
                    break;
                }
            }

		}

		//define url atual caso não tenha nenhuma definida e seta production
		if($this->getEnvironmentStatus()==''){
			$this->setEnvironmentStatus('production');
			$c['address_uri'] = $_SERVER['HTTP_HOST'];
			if(substr($c['address_uri'], -1)!='/'){
				$c['address_uri'].='/';
			}
		}

		//define url atual caso não tenha nenhuma definida e seta production
		if($this->getEnvironmentStatus()==''){
		    $this->setEnvironmentStatus('production');
		    $c['address_uri'] = $_SERVER['HTTP_HOST'];
		    if(substr($c['address_uri'], 0,4)!='www.'){
		            $c['address_uri'] = substr($_SERVER['HTTP_HOST'],4);
		    }
		    if(substr($c['address_uri'], -1)!='/'){
		            $c['address_uri'].='/';
		    }
		}

		if($this->getEnvironmentStatus()=='production' || $this->getEnvironmentStatus()=='staging'){
        	$dir_path = 'prod';
        }else if($this->getEnvironmentStatus()=='testing' || $this->getEnvironmentStatus()=='development'){
        	$dir_path = 'dev';
        	if(file_exists(PATH_ROOT.'/app/dev/') && file_exists(PATH_ROOT.'/app/prod/')){
        		unlink(file_exists(PATH_ROOT.'/app/dev/'));
        	}
        }

    	if(!file_exists(PATH_ROOT.'/app/'.$dir_path)){
    		if($this->getEnvironmentStatus()!='development' && $this->getEnvironmentStatus()!='testing' && file_exists(PATH_ROOT.'/app/dev/')){
    			rename(PATH_ROOT.'/app/dev/', PATH_ROOT.'/app/'.$dir_path );
    		}else if($this->getEnvironmentStatus()!='production' && $this->getEnvironmentStatus()!='staging' && file_exists(PATH_ROOT.'/app/prod/')){
    			rename(PATH_ROOT.'/app/prod/', PATH_ROOT.'/app/'.$dir_path );
    		}
    	}
        $this->setApplicationPath('/app/'.$dir_path.'/'.$this->getApplicationName());


		//Get address_uri of file app_config.yml
		if($this->getEnvironmentStatus()=='production'){
			if(isset($app_config[$this->getNameApplication()]['production'])){
				$c=$app_config[$this->getNameApplication()]['production'];
			}
		}else if($this->getEnvironmentStatus()=='staging'){
			if(isset($app_config[$this->getNameApplication()]['staging'])){
				$c=$app_config[$this->getNameApplication()]['staging'];
			}
		}else if($this->getEnvironmentStatus()=='testing'){
			if(isset($app_config[$this->getNameApplication()]['testing'])){
				$c=$app_config[$this->getNameApplication()]['testing'];
			}
		}else if($this->getEnvironmentStatus()=='development'){
			if(isset($app_config[$this->getNameApplication()]['development'])){
				$c=$app_config[$this->getNameApplication()]['development'];
			}
		}

		if(strpos($c['address_uri'], '/')){
			$search = explode('/', $c['address_uri']);
			$search = $search[0];
		}else{
			$search = $c['address_uri'];
		}

		if($_SERVER['HTTP_HOST']!=$search){
			if(strpos($_SERVER['HTTP_HOST'], $search)!==false){
				$t = explode($search, $_SERVER['HTTP_HOST']);
				$c['address_uri'] = $t[0].$c['address_uri'];
			}else{
				$c['address_uri'] = $_SERVER['HTTP_HOST'];
			}
		}
		if(substr($c['address_uri'], -1)!='/'){
			$c['address_uri'].='/';
		}



		$this->setAddressUri($c['address_uri']);
		$this->setUseHttps($c['use_https']);

		if($c['use_www']!='On' && $c['use_www']!='Off' && substr($_SERVER['HTTP_HOST'], 0,4)=='www.'){
			$c['use_www']='On';
		}else{
			$c['use_www']='Off';
		}
		$this->setUseWww($c['use_www']);


		if($this->getAddressUri()==''){
			throw new \Exception("Você deve definir uma url em address_uri_".$this->getEnvironmentStatus().' no arquivo de configuração do app_config.yml');
		}

		$this->setConnectionDb($app_config);

	}

	// realiza as configurações do sistema
	private function setSystemConfigs(){
		/* Set limiter cache, default: private */
		if($this->getCacheLimiter() != 'nochace' && $this->getCacheLimiter() != 'private' && $this->getCacheLimiter() != 'private_no_expire' && $this->getCacheLimiter() != 'public'){
			$this->getCacheLimiter('private');
		}
		session_cache_limiter($this->getCacheLimiter());


		//Update path to save sessions
		if(!file_exists(PATH_ROOT.$this->getSessionSavePath()) || $this->getSessionSavePath()==''){
			mkdir(PATH_ROOT.$this->getSessionSavePath(), 0777, true);
		}

		/* Set time in seconds for expite sessions */
		session_cache_expire((empty($this->getCacheExpire())?1000:$this->getCacheExpire()));
		session_save_path(PATH_ROOT.$this->getSessionSavePath());

		session_start();
		ob_start();


		$dir_session = session_save_path();
		if ($handle = opendir($dir_session)) {
		  foreach (glob($dir_session."sess_*") as $filename) {
		    if (filemtime($filename) + $this->getCacheExpire() < time()) {
		      @unlink($filename);
		    }
		  }
		}

		/*Set time zone for system */
		if($this->getChaceNavegation()==false){
			header('Expires: Sun, 01 Jan 2017 00:00:00 GMT');
			header('Cache-Control: no-store, no-cache, must-revalidate');
			header('Cache-Control: post-checkcheck=0, pre-check=0', FALSE);
			header('Pragma: no-cache');
		}

		/*Set time zone for system */
		if($this->getDateTimezone()!=''){
			ini_set('date.timezone', $this->getDateTimezone());
		}
		if($this->getUploadMaxFilesize()!=''){
			ini_set("upload_max_filesize", $this->getUploadMaxFilesize());
		}
		if($this->getPostMaxSize()!=''){
			ini_set('post_max_size',$this->getPostMaxSize());
		}

		$log = new Logger($this->getProjectName());
		ErrorHandler::register($log);
		$log->pushHandler(new StreamHandler(PATH_ROOT.$this->getErrorLog(), Logger::DEBUG));
		$log->pushHandler(new FirePHPHandler());

		if($this->getEnvironmentStatus() == 'production'){
			$this->setDisplayErrors('Off');
			$this->setErrorReporting('0');
		}

		// error log
		if($this->getDisplayErrors()!='On' && $this->getDisplayErrors()!='Off' && $this->getDisplayErrors()!=''){
			throw new \Exception("setSystemConfigs() Valor de display_errors deve ser 'On', 'Off' ou ''.");
		}else if($this->getDisplayErrors()!=''){
			ini_set('display_errors'	, 	$this->getDisplayErrors());
		}

		//Set if have log errors
		if($this->getLogErrors()!='On' && $this->getLogErrors()!='Off' && $this->getLogErrors()!=''){
			throw new \Exception("setSystemConfigs() Valor de log_errors deve ser 'On', 'Off' ou ''.");
		}else if($this->getLogErrors()!=''){
			ini_set('log_errors'	, 	$this->getLogErrors());
		}


		//Set o tipo de erro
		if($this->getErrorReporting()!='' && array_search($this->getErrorReporting(), array("E_ERROR","E_WARNING","E_PARSE","E_NOTICE","E_CORE_ERROR","E_CORE_WARNING","E_COMPILE_ERROR","E_COMPILE_WARNING","E_USER_ERROR","E_USER_WARNING","E_USER_NOTICE","E_ALL","E_STRICT","E_RECOVERABLE_ERROR","0"))==''){
			$this->setErrorReporting('E_ALL');
		}
		if($this->getErrorReporting()!=''){

			switch ($this->getErrorReporting()) {
				case "E_ERROR"				: error_reporting(E_ERROR); 				break;
				case "E_WARNING"			: error_reporting(E_WARNING); 				break;
				case "E_PARSE"				: error_reporting(E_PARSE); 				break;
				case "E_NOTICE"				: error_reporting(E_NOTICE); 				break;
				case "E_CORE_ERROR"			: error_reporting(E_CORE_ERROR); 			break;
				case "E_CORE_WARNING"		: error_reporting(E_CORE_WARNING); 			break;
				case "E_COMPILE_ERROR"		: error_reporting(E_COMPILE_ERROR); 		break;
				case "E_COMPILE_WARNING"	: error_reporting(E_COMPILE_WARNING); 		break;
				case "E_USER_ERROR"			: error_reporting(E_USER_ERROR); 			break;
				case "E_USER_WARNING"		: error_reporting(E_USER_WARNING); 			break;
				case "E_USER_NOTICE"		: error_reporting(E_USER_NOTICE); 			break;
				case "E_ALL"				: error_reporting(E_ALL); 					break;
				case "E_STRICT"				: error_reporting(E_STRICT); 				break;
				case "E_RECOVERABLE_ERROR"	: error_reporting(E_RECOVERABLE_ERROR); 	break;

				default: error_reporting(0); break;
			}
		}

		/* Define o limitador de cache para 'private' */
		if($this->getLocale()==''){
			$this->setLocale('pt_BR');
		}
		setlocale(LC_CTYPE, $this->getLocale());
	}


	public function getAppConfigYaml(){
		if(file_exists(PATH_ROOT.'/app/app_config.yml')){
			try {
				$app_config = Yaml::parse(file_get_contents(PATH_ROOT.'/app/app_config.yml'));
				if(count($app_config)==0){
					throw new \Exception("Você deve configurar o arquivo app_config.yml");
				}

				if(!isset($app_config['default']['development']['address_uri']) || strlen($app_config['default']['development']['address_uri'])<5){
					if(strpos($_SERVER['HTTP_HOST'], 'www')===false){
						$app_config['default']['development']['address_uri'] = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
						$app_config['default']['development']['use_https'] = 'Off';
						$app_config['default']['development']['use_www'] = 'Off';
					}else{
						$app_config['default']['development']['address_uri'] = str_replace('www.', '',  $_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI'];
						$app_config['default']['development']['use_https'] = 'Off';
						$app_config['default']['development']['use_www'] = 'On';
					}

					file_put_contents(PATH_ROOT.'/app/app_config.yml', str_replace(array("'On'","'Off'"), array("On","Off"), Yaml::dump($app_config , 5 , 5)));
				}
			}catch(ParseException $e){
			    throw new \Exception("Não é possível analisar dados app_config.yml : %s ".$e->getMessage());
			}
			return $app_config;
		}else{
			throw new \Exception("Arquivo de configuração 'app_config.yml' não encontrado em: ".PATH_ROOT.'/app/');
		}
	}

	public function setConnectionDb($app_config=array()){
		//Get address_uri of file app_config.yml
		if($this->getEnvironmentStatus()=='production'){
			if(isset($app_config[$this->getNameApplication()]['db_production'])){
				$this->setDbConfig($app_config[$this->getNameApplication()]['db_production']);
			}
		}else if($this->getEnvironmentStatus()=='staging'){
			if(isset($app_config[$this->getNameApplication()]['db_staging'])){
				$this->setDbConfig($app_config[$this->getNameApplication()]['db_staging']);
			}
		}else if($this->getEnvironmentStatus()=='testing'){
			if(isset($app_config[$this->getNameApplication()]['db_testing'])){
				$this->setDbConfig($app_config[$this->getNameApplication()]['db_testing']);
			}
		}else if($this->getEnvironmentStatus()=='development'){
			if(isset($app_config[$this->getNameApplication()]['db_development'])){
				$this->setDbConfig($app_config[$this->getNameApplication()]['db_development']);
			}
		}


		$db=$this->getDbConfig();
		if(isset($db) && !empty($db['host']) && !empty($db['username']) && !empty($db['password']) && !empty($db['database'])){
			$GLOBALS['CONN'] = ADONewConnection('mysqli'); # eg. 'mysql','mysqlI' or 'oci8'
			$GLOBALS['CONN']->Connect($db['host'], $db['username'], $db['password'], $db['database']);
			$GLOBALS['CONN']->Execute("SET NAMES '".$db['encoding']."'");
		  	$GLOBALS['CONN']->Execute("SET character_set_connection=".$db['encoding']);
		  	$GLOBALS['CONN']->Execute("SET character_set_client=".$db['encoding']);
		  	$GLOBALS['CONN']->Execute("SET character_set_results=".$db['encoding']);
		}else{
			return false;
		}
	}

	// realiza as configurações da atual aplicação
	private function setSystemConfigsCurrentApplication(){
		//verifica use_https
		if($this->getUseHttps()!='On' && $this->getUseHttps()!='Off'){
			if((isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO']=='https') || (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")){
                $this->setUseHttps('On');
            }else{
                $this->setUseHttps('Off');
            }
        }
		//verifica use_www
		if($this->getUseWww()!='On' && $this->getUseWww()!='Off'){
			$this->setUseWww('Off');
		}
		//verifica address_uri
		if($this->getAddressUri()=='' || preg_match('/^http/', $this->getAddressUri()) || (preg_match('/^www/', $this->getAddressUri())) && $this->getEnvironmentStatus()!='production' ){
			throw new \Exception("setSystemConfigsCurrentApplication() Valor de address_uri deve conter a url da aplicação sem http e sem www. ex: google.com");
		}
		//define url padrão da aplicação
		$this->setPathBaseHref(($this->getUseHttps()=='On'?'https://':'http://').($this->getUseWww()=='On'?'www.':'').str_replace('www.','',$this->getAddressUri()));

		$tmp=explode('/', preg_replace('/^[\/]*(.*?)[\/]*$/', '\\1', $_SERVER['REQUEST_URI']));

		$k=array_search('public', $tmp);
		if($k!=''){
			unset($tmp[$k]);
		}


        if($this->getUseHttps()=='On' || (preg_match('/^www/', $this->getAddressUri()) && $this->getUseWww()=='Off') || (!preg_match('/^www/', $this->getAddressUri()) && $this->getUseWww()=='On') || $k!=''){
            if(
            	( (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO']!='https') || ((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "on"))) || 
            	(preg_match('/^www/', $_SERVER["HTTP_HOST"])===1 && $this->getUseWww()=='Off') || 
            	(preg_match('/^www/', $_SERVER["HTTP_HOST"])===0 && $this->getUseWww()=='On')
            ){
               header( 'Location: '.$this->getPathBaseHref() ); exit;
            }
        }


	}
	// Set applications of system
	public function setApplication($a='',$l=''){
		if(is_string($a)){
			// adicona novas applications
			// 'default' => /app/default/ é padrão
			$this->application[$a] = $l;
		}else if(is_array($a)){
			foreach ($a as $key => &$value) {
				$this->application[$key] = $value;
			}
		}
	}
	// Get applicatoins disponible
	public function getApplication(){
		return $this->application;
	}

	// Capture path of application, using url or subdomin
	public function getApplicationUseNow(){
		$tmp=explode('/', preg_replace('/^[\/]*(.*?)[\/]*$/', '\\1', $_SERVER['REQUEST_URI']));


		foreach ($tmp as $key => $value) {
			if(isset($tmp[$key]) && is_array($this->getApplication()) && array_key_exists($tmp[$key],$this->getApplication())){
				for ($i=0; $i < $key; $i++) {
					array_shift($tmp);
				}
				$app_path = $this->getApplication();
				$app_public = $app_path[$tmp[0]]['path_public'];
				$app_path = $app_path[$tmp[0]]['path_app'];
				$this->setNameApplication($tmp[0]);
			}
		}

		for ($i=0; $i < count($key); $i++) {
			array_shift($tmp);
		}

		if(!isset($app_path)){
			$app_path = $this->getApplication();
			$app_public=$app_path['default']['path_public'];
			$app_path=$app_path['default']['path_app'];
			$this->setNameApplication('default');
		}

		$this->setApplicationName($app_path);
		if(!file_exists(PATH_ROOT.$app_public)){
			throw new \Exception("app_public não foi encontrado em: ".PATH_ROOT.$app_public);
		}else{
			$this->setPublicPath($app_public);
		}

	}

	private function notificationErrors($title='',$text=''){
		$e = new \DwPhp\ErrorViewPHP($title,$text);
		echo $e->showErrorPhp();
		exit();
	}

	public function writeErrorSQL($erro=''){
		// "a" representa que o arquivo é aberto para ser escrito
		$fp = fopen(PATH_ROOT.$this->getErrorSql(), "a+");
		// Escreve "exemplo de escrita" no log
		$escreve = fwrite($fp, $erro."\n");
		// Fecha o arquivo
		fclose($fp);
	}

	/* CONTROLS PAGES APPLICATION */
	//retur folder about aplicatoins
	private function getApplicationPathFiles(){

		$this->setPathURI(explode('/', preg_replace('/^[\/]*(.*?)[\/]*$/', '\\1', $_SERVER['REQUEST_URI'])));
		$a=$this->getPathURI();


		if($this->getEnvironmentStatus()=='development'){
			$nun_level=explode("/", $this->getAddressUri());

			for ($i=0; $i < count($nun_level)-2; $i++) {
				array_shift($a);
			}
			$this->setPathURI($a);
		}
		foreach ($a as $key => $value) {
			if($this->getNameApplication()==$value){
				unset($a[$key]);
			}
		}

		if(reset($a)=='helpers'){
			array_shift($a);
			$this->setHelpers(true);
		}else{
			$this->setHelpers(false);
		}
		$this->setPathURI($a);

		foreach ($a as $key => $value) {
			$this->posURL[]=$value;
		}


		$url_array=$this->getPathURI();

		$directory_action=$directory_ctrl=$directory_view='';

		if($this->getHelpers()==true){
			$directory_action = $this->getPathApplication().'helpers';
			do{
				if( in_array(current($url_array), scandir($directory_action))){
					$directory_action.='/'.current($url_array);
					next($url_array);
					$dir = false;

				}else if( in_array(current($url_array).'.php', scandir($directory_action))){
					$directory_action.='/'.current($url_array).'.php';
					next($url_array);
					if(isset($url_array[key($url_array)])){
						$this->setMethodsURI($url_array[key($url_array)]);
					}
					$dir = true;
				}else{
					$directory_action.='/index.php';
					$dir = true;
				}
			}while(!$dir);
		}else{
			$directory_ctrl = $this->getPathApplication().'controllers';
			$directory_view = $this->getPathApplication().'views/pages/default';
			$this->urlCompletePath = substr($this->getPathBaseHref(),0,-1);
			do{
				if( (file_exists($directory_view) && in_array(current($url_array), scandir($directory_view))) || (file_exists($directory_ctrl) && in_array(current($url_array), scandir($directory_ctrl))) ){
					$directory_ctrl.='/'.current($url_array);
					$directory_view.='/'.current($url_array);
					$this->urlCompletePath.='/'.(current($url_array)!='index'?current($url_array):'');
					next($url_array);
					$dir = false;
					if(isset($url_array[key($url_array)])){
						$this->setMethodsURI($url_array[key($url_array)]);
					}
				}else if( (file_exists($directory_view) && in_array(current($url_array).'.php', scandir($directory_view))) || (file_exists($directory_ctrl) && in_array(current($url_array).'.php', scandir($directory_ctrl))) ){
					$directory_ctrl.='/'.current($url_array).'.php';
					$directory_view.='/'.current($url_array).'.php';
					$this->urlCompletePath.='/'.(current($url_array)!='index'?current($url_array):'');
					next($url_array);
					if(isset($url_array[key($url_array)])){
						$this->setMethodsURI($url_array[key($url_array)]);
					}
					$dir = true;
				}else{
					if(isset($url_array[key($url_array)])){
						$this->setMethodsURI($url_array[key($url_array)]);
					}

					if((count($url_array)==0 || is_int(key($url_array)) == false) || (isset($url_array) && $url_array[0]=='')){
						$directory_ctrl.='/index.php';
						$directory_view.='/index.php';
						$this->urlCompletePath.='/';
					}
					$dir = true;
				}

			}while(!$dir);

		}

		//verifica se existe a view
		if($this->getHelpers()==true && file_exists($directory_action) && is_file($directory_action) ){
			// inicia actoin
			$this->setPageAction($directory_action);
			if (strpos($_SERVER['HTTP_ACCEPT'], 'htm') === false) {	
				$this->instanceTemplate($this->getPageAction());
			}else{
				http_response_code(505);
				$this->setHelpers(false);
				$this->setPageView($this->getPathApplication('views/error/','404.php'));
			}
		}else if((file_exists($directory_ctrl) && is_file($directory_ctrl)) || (file_exists($directory_view) && is_file($directory_view))){
			// inicia controller
			$this->setPageCtrl($directory_ctrl);
			$this->setPageView($directory_view);
		}
		if(!file_exists($directory_ctrl) || !is_file($directory_ctrl)){
			$this->setPageCtrl($this->getPathApplication('controllers/error/','404.php'));
		}
		if(!file_exists($directory_view) || !is_file($directory_view)){
			$this->setPageView($this->getPathApplication('views/error/','404.php'));
		}

		if($this->getHelpers()==false){
			$this->instanceTemplate($this->getPathApplication('views/layout/','template.php'));
		}

	}

	/* GET PATH */
	public function insertLoadPageLog($timer= '', $page=''){
		if($this->getLimitDataLoadPage()!=0){
			$text='';
			$addLine=true;
			if(file_exists(PATH_ROOT."/storage/log/loadpage.log")){
				$f=fopen(PATH_ROOT."/storage/log/loadpage.log","r+");
				while (!feof($f)) {
					//pega conteudo da linha
					$line=fgets($f);
					if($line!=''){
						$lines=explode('|',$line);
						if(isset($lines[0]) && $lines[0]==$page){
							//define que o arquivo será reescrito
							$addLine=false;
							$text=substr($text, 0,-1);
							//$text.=$timer.";\n";

							$lines[1] = explode(';',$lines[1]);
							array_pop($lines[1]);
							if(count($lines[1])>$this->getLimitDataLoadPage()-1){
								while ( count($lines[1])>(int)$this->getLimitDataLoadPage()-1) {
									array_shift($lines[1]);
								}
							}
							$t=implode($lines[1], ";");
							if(strlen($text)!=0){
								$text.= "\n";
							}
							$text.= $page."|".$t.';'.$timer.";\n";
							//echo $page."|".$t.$timer.";\n";
						}else{
							$text.=$line;
						}
					}
				}
				fclose($f);
			}

			$f2=fopen(PATH_ROOT."/storage/log/loadpage.log","w+");
			if($addLine==true){
				$text.=$page.'|'.$timer.";\n";
			}
			fwrite($f2, $text);
			fclose($f2);
		}
	}

	/* GET PATH */
	public function getPathApplication($base='',$file = ''){
		return
			PATH_ROOT.
			$this->getApplicationPath().'/'.
			$base.
			$file;
	}

	/* GET PATH */
	private function instanceTemplate($pathFile){
		if(file_exists($pathFile)){
			require_once $pathFile;
			if(class_exists('App\\template', true)){
				$n='App\template';
				$this->template = new $n($this);
				
				if(file_exists($this->getpageCtrl())){
					require_once $this->getpageCtrl();
					if(class_exists('\App\Framework\controller', false)){
						$this->controller = new \App\Framework\controller($this);
						if($this->getMethodsURI()!=''){
							if(method_exists($this->controller,$this->getMethodsURI())){
								$methodsAction=$this->getMethodsURI();
								$this->controller->$methodsAction();
							}
						}
						if(method_exists($this->controller,'show')){
							$this->controller->show();
						}
						if(isset($this->ctrlFunction) && !empty($this->ctrlFunction)){
							try{
								$methods = get_class_methods($this->controller);
								$function = $this->ctrlFunction;
								if(in_array($function, $methods)){
									$this->controller->$function();
								}else{
									throw new \Exception(utf8_decode("Não foi encontrada a função " . $function . " no controller " . $this->getPageCtrl()));
								}
							}catch(\Exception $e){
								$this->notificationErrors('Função não encontrada:' ,$e->getMessage()); exit;
							}
						}
					}
					if($this->getPageView() == ''){ exit; }
				}
				try {
					if(isset($this->controller)){
						$this->controller->constructPage();
					}else if(isset($this->template)){
						$this->template->constructPage();
					}
				} catch (Exception $e) {
					$this->notificationErrors('Não inciado constructPage', $e->getMessage()); exit;
				}
			}else if(class_exists('App\\Helpers\\Ajax\\Main', true)){

				$n='App\\Helpers\\Ajax\\Main';
				$this->action = new $n($this);
				if($this->getMethodsURI()!=''){
					$GLOBALS['f'] = $this;
					header('Cache-Control: no-cache, must-revalidate');
					header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
					header('Content-type: application/json');
					
					if(method_exists($this->action,$this->getMethodsURI())){
						$methodsAction=$this->getMethodsURI();
						http_response_code(200);
						$this->action->$methodsAction();
					}else{
						http_response_code(404);
					}
				}
			}else if(class_exists('App\\action', true)){
				$n='App\action';
				$this->action = new $n($this);
			}

		}
	}

	public function fileVersion($localFile=''){
		// retorna string
		$dir=$this->getPublicPath();
		if(strpos($localFile,"/public/")===false){
			$p=explode('/',$this->getPublicPath());
			unset($p[1]);
			$p=implode('/',$p);
		}else{
			$dir='';
		}
		if(file_exists(PATH_ROOT.$dir.$localFile) && $localFile!=''){
		    return substr($this->getPathBaseHref(),0,-1).(isset($p) && $p=='/default'?$p:'').$localFile.'?'.md5(filemtime(PATH_ROOT.$dir.$localFile)); exit;
		}else{
		    return $localFile;
		}
	}

	/**
	 GETTERS AND SETTERES
	 */

	public function requireModels($file=''){
		//$d = PATH_ROOT.$this->getApplicationPath().'/'.$this->getEnvironmentStatus().'/models'.($file!=''?'/'.$file:'');
		$d = PATH_ROOT.'/app/'.$this->getEnvironmentStatus().'/models'.($file!=''?'/'.$file:'');
		if(file_exists($d) && is_file($d)){
			require_once $d;
		}
	}

	public function getApplicationPath(){
	    return $this->applicationPath;
	}

	public function setApplicationPath($applicationPath){
	    $this->applicationPath = $applicationPath;
	    return $this;
	}

	public function getApplicationName(){
	    return $this->applicationName;
	}

	public function setApplicationName($applicationName){
	    $this->applicationName = $applicationName;
	    return $this;
	}

	public function getLocalFileRead(){
	    return $this->localFileRead;
	}

	public function setLocalFileRead($localFileRead){
	    $this->localFileRead = $localFileRead;
	    return $this;
	}

	public function getPublicPath(){
	    return $this->publicPath;
	}

	public function setPublicPath($publicPath){
	    $this->publicPath = $publicPath;
	    return $this;
	}

	public function getEnvironmentStatus(){
	    return $this->environmentStatus;
	}

	public function setEnvironmentStatus($environmentStatus){
	    $this->environmentStatus = $environmentStatus;
	    return $this;
	}

	public function getNameApplication(){
	    return $this->nameApplication;
	}

	public function setNameApplication($nameApplication){
	    $this->nameApplication = $nameApplication;
	    return $this;
	}

	public function setPathApplication($pathApplication){
	    $this->pathApplication= $pathApplication;
	    return $this;
	}

	public function getDisplayErrors(){
	    return $this->displayErrors;
	}

	public function setDisplayErrors($displayErrors){
	    $this->displayErrors = $displayErrors;
	    return $this;
	}

	public function getLogErrors(){
	    return $this->logErrors;
	}

	public function setLogErrors($logErrors){
	    $this->logErrors = $logErrors;
	    return $this;
	}

	public function getErrorLog(){
	    return $this->errorLog;
	}

	public function setErrorLog($errorLog){
	    $this->errorLog = $errorLog;
	    return $this;
	}

	public function getErrorSql(){
	    return $this->errorSql;
	}

	public function setErrorSql($errorSql){
	    $this->errorSql = $errorSql;
	    return $this;
	}

	public function getErrorReporting(){
	    return $this->errorReporting;
	}

	public function setErrorReporting($errorReporting){
	    $this->errorReporting = $errorReporting;
	    return $this;
	}

	public function getCacheLimiter(){
	    return $this->cacheLimiter;
	}

	public function setCacheLimiter($cacheLimiter){
	    $this->cacheLimiter = $cacheLimiter;
	    return $this;
	}

	public function getSessionSavePath(){
	    return $this->sessionSavePath;
	}

	public function setSessionSavePath($sessionSavePath){
	    $this->sessionSavePath = $sessionSavePath;
	    return $this;
	}

	public function getCacheExpire(){
	    return $this->cacheExpire;
	}

	public function setCacheExpire($cacheExpire){
	    $this->cacheExpire = $cacheExpire;
	    return $this;
	}

	public function getLocale(){
	    return $this->locale;
	}

	public function setLocale($locale){
	    $this->locale = $locale;
	    return $this;
	}

	public function getDateTimezone(){
	    return $this->dateTimezone;
	}

	public function setDateTimezone($dateTimezone){
	    $this->dateTimezone = $dateTimezone;
	    return $this;
	}

	public function getUploadMaxFilesize(){
	    return $this->uploadMaxFilesize;
	}
	public function setUploadMaxFilesize($uploadMaxFilesize){
	    $this->uploadMaxFilesize = $uploadMaxFilesize;
	    return $this;
	}


	public function getPostMaxSize(){
	    return $this->postMaxSize;
	}

	public function setPostMaxSize($postMaxSize){
	    $this->postMaxSize = $postMaxSize;
	    return $this;
	}

	public function getChaceNavegation(){
	    return $this->chaceNavegation;
	}

	public function setChaceNavegation($chaceNavegation){
	    $this->chaceNavegation = (boolean)$chaceNavegation;
	    return $this;
	}

	public function getPosUrl($num=''){
		if(is_int($num) || $num!=''){
			return !empty($this->posURL[(int)$num]) ? $this->posURL[(int)$num] : false;
		}else{
			return $this->posURL;
		}
	}

	public function getPathURI(){
	    return $this->pathURI;
	}

	public function setPathURI($pathURI){
	    $this->pathURI = $pathURI;
	    return $this;
	}

	public function getPageAction(){
	    return $this->pageAction;
	}

	public function setPageAction($pageAction){
	    $this->pageAction = $pageAction;
	    return $this;
	}

	public function getPageCtrl(){
	    return $this->pageCtrl;
	}

	public function setPageCtrl($pageCtrl){
	    $this->pageCtrl = $pageCtrl;
	    return $this;
	}

	public function getPageModel(){
	    return $this->pageModel;
	}

	public function setPageModel($pageModel){
	    $this->pageModel = $pageModel;
	    return $this;
	}

	public function getPageView(){
	    return $this->pageView;
	}

	public function setPageView($pageView){
	    $this->pageView = $pageView;
	    return $this;
	}

	public function getCtrlFunction(){
	    return $this->ctrlFunction;
	}

	public function setCtrlFunction($ctrlFunction){
	    $this->ctrlFunction = $ctrlFunction;
	    return $this;
	}

	public function getUseHttps(){
        return $this->useHttps;
    }

    public function setUseHttps($useHttps){
        $this->useHttps = $useHttps;

        return $this;
    }

    public function getUseWww(){
        return $this->useWww;
    }

    public function setUseWww($useWww){
        $this->useWww = $useWww;

        return $this;
    }

    public function getAddressUri(){;
        return $this->addressUri;
    }

    public function setAddressUri($addressUri){
        $this->addressUri = $addressUri;

        return $this;
    }

    public function getUrlCompletePath(){
        return $this->urlCompletePath.(substr($this->urlCompletePath,-1)!='/'?'/':'');
    }

    public function getPathBaseHref(){
        return $this->pathBaseHref;
    }

    public function setPathBaseHref($pathBaseHref){
        $this->pathBaseHref = $pathBaseHref;

        return $this;
    }


    public function getDbConfig(){
        return $this->dbConfig;
    }

    public function setDbConfig($dbConfig){
        $this->dbConfig = $dbConfig;

        return $this;
    }

    public function getMethodsURI(){
        return $this->methodsURI;
    }

    public function setMethodsURI($methodsURI){
        $this->methodsURI = $methodsURI;

        return $this;
    }

    public function getLimitDataLoadPage(){
        return $this->limitDataLoadPage;
    }

    public function setLimitDataLoadPage($limitDataLoadPage){
        $this->limitDataLoadPage = (int)$limitDataLoadPage;

        return $this;
    }

	public function getHelpers(){
        return $this->helpers;
    }

    public function setHelpers($helpers){
        $this->helpers = (boolean)$helpers;

        return $this;
    }

	public function getProjectName(){ 
		return $this->projectName;
	}

	public function setProjectName($projectName){ 
		$this->projectName = $projectName;

		return $this;
	}
}
