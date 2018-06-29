<?php

/**
 * @Author: Cleberson Bieleski
 * @Date:   2017-12-23 04:54:45
 * @Last Modified by:   Cleber
 * @Last Modified time: 20-04-2018 09:36:07
 */
	namespace DwPhp;
	use App\Framework\controller;
	abstract class Framework{
		public $namePage;
		public $template = 'default';
		public $f;
		private $str_keys;
		private $str_value;

		/*
			construtor que recebe f e chama o m�todo
			initialize. N�o pode sofrer sobrecarga
		*/
		final function __construct(&$fRef){
			$f = $GLOBALS['f']  = &$fRef;
			$this->initialize();
		}
		/*
			sub m�todo construtor que pode ser sobrecarregado
			na superclasse.
		*/
		protected function initialize(){}
		/*
			exibe a pagina para o usuario. � criada a variavel $f
			referenciano $this->f pois o arquivo incluido fica no
			escopo deste metodo.
		*/
		final public function constructPage(){
			$f = &$GLOBALS['f'];

			$baseFiles=$GLOBALS['f']->getPathApplication('views/layout/',$this->getTemplate().'/_init.php');
			if(!file_exists($baseFiles)){
				throw new Exception("N�o encontrado: ".$GLOBALS['f']->getPathApplication('views/layout/',$this->getTemplate().'/_init.php'));
			}
			$this->showHTML($baseFiles);
			echo $this->HTMLaddFooter();

		}
		/* GETTRES AND SETTRES */
		public function getTemplate(){
			return $this->template;
		}
		public function setTemplate($template){
			$this->template = $template;
			return $template;
		}

		/* GETTRES AND SETTRES */
		public function getNamePage(){
			return $this->namePage;
		}
		public function setNamePage($namePage){
			$this->namePage = $namePage;
			return $namePage;
		}

		/* GETTRES AND SETTRES */
		public function getHtml(){
			return $this->html;
		}
		public function setHtml($html){
			$this->html = $html;
			return $html;
		}

		/*
			m�todos que, por padr�o, s�o chamados no Template.
		*/
 		public function HTMLaddFooter(){
 			list($usec, $sec) = explode(" ", MICROTIME);
 			$script_start = (float) $sec + (float) $usec;

 			list($usec, $sec) = explode(" ", microtime());
			$script_end = (float) $sec + (float) $usec;
			$elapsed_time = round($script_end - $script_start, 8);

			$p=$this->getNamePage();

			if(isset($p) || !empty($p)){
				$GLOBALS['f']->insertLoadPageLog($elapsed_time,$p);
				return "\n<!-- executado em ".$elapsed_time." segundos -->";
			}
		}
	}