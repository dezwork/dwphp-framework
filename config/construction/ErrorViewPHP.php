<?php

namespace DwPhp;

class ErrorViewPHP{

	public $errorPhp = '';
	public $titleErrorPhp = '';

	function __construct($title='',$txt=''){
		if($txt==''){
			return '';
		}else{
			error_log(strip_tags($txt), 0);
			$this->setTitleErrorPhp($title);
			$this->setErrorPhp($txt);
		}
	}

	public function showErrorPhp(){
			$ret='';
			$ret.='	<html>';
			$ret.='	<head>';
			$ret.='		<title>Inconsistência no sistema;</title>';
			$ret.='		<style>';
			$ret.='  		*{ font-family: arial}';
			$ret.='		</style>';
			$ret.='	</head>';
			$ret.='	<body>';
			$ret.='		<div style="width: 80%; margin: 5% auto; max-width: 700px; text-align: center"><h2>'.$this->getTitleErrorPhp().'</h2>'.$this->getErrorPhp().'</div>';
			$ret.='		</body>';
			$ret.='	</html>';

			return $ret;
	}

	public function setErrorPhp($v=''){
		$this->errorPhp = $v;
		return $this;
	}
	public function getErrorPhp(){
		return $this->errorPhp;
	}

	public function setTitleErrorPhp($v=''){
		$this->titleErrorPhp = $v;
		return $this;
	}
	public function getTitleErrorPhp(){
		return $this->titleErrorPhp;
	}

}