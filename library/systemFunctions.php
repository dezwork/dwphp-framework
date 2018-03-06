<?php

/**
 * @Author: Cleberson Bieleski
 * @Date:   2017-12-23 04:54:45
 * @Last Modified by:   Cleber
 * @Last Modified time: 2018-03-06 17:01:31
 */

namespace DwPhp\Library;


	class systemFunctions{
		/* ADD SLASHES - chamada em framework */
		public static function addSlashes($var){
			if(!get_magic_quotes_gpc())
				$var = self::execute($var, 'addslashes');
			return $var;
		}
		/* EXECUTE */
		public static function execute($var, $func){
			if(is_array($var))
				foreach($var as $index => $value)
					$var[$index] = self::execute($value, $func);
			else
				$var = $func($var);
			return $var;
		}


		public static function GET($positoin_post=''){
			return filter_input(INPUT_GET , $positoin_post);
		}
		public static function POST($positoin_post=''){
			return filter_input(INPUT_POST , $positoin_post);
		}


		public function posURI($num=0){
			$tmp=explode('/', preg_replace('/^[\/]*(.*?)[\/]*$/', '\\1', $_SERVER['REQUEST_URI']));

			if(isset($tmp[$num])){
				return $tmp[$num];
			}else{
				return '';
			}
		}

		//cria url amigavel conforme nome passado
		public static function UrlAmigavel($string){
		    $table = array('Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z','ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c','À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A','Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I','Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O','Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U','Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a','å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e','ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i','ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o','ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u','ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b','ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r');
	    	$string = strtr($string, $table);
	    	$string = strtolower($string);
	    	$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
	    	$string = preg_replace("/[\s-]+/", " ", $string);
	    	$string = preg_replace("/[\s_]/", "-", $string);
	    	return $string;
		}

		//cria um código aleatório conforme solicitado via parâmetro
		public static function ShortURL($tamanho=10,$maiusculas=true,$numeros=true){
			$lmin = 'abcdefghijklmnopqrstuvwxyz';
			$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$num  = '1234567890';
			$retorno  = '';
			$caracteres  = '';
			$caracteres .= $lmin;
			if($maiusculas) $caracteres.= $lmai;
			if($numeros) $caracteres.= $num;
			$len = strlen($caracteres);
			for($n=1;$n<= $tamanho;$n++){
				$rand = mt_rand(1,$len);
				$retorno .=$caracteres[$rand-1];
			}
			return $retorno;
		}

		# SISTEMA - TRATAMENTOS DE DADOS #
		/* HEADER AJAX */
		public static function headerJson($objJson=array()){
			if(strpos($_SERVER['SCRIPT_NAME'], 'phpunit')===false){
				header('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
				header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
				header('Cache-Control: no-cache, must-revalidate');
				header('Pragma: no-cache');
				header('Content-Type: application/json');
			}

			$a_tmp = array();

			foreach ($objJson as $key => $value) {
				$a_tmp[] = '"'.$key.'":"'.$value.'"';
			}

			return json_decode('{'.implode(',',$a_tmp).'}');


		}

		public static function OrdemUriForSQL($url){
			$l=parse_url($url);
			$vars='';
			if (isset($l['query']) && stripos($l['query'],'&') === false) {
			    $v_tmp=explode('=',$l['query']);
				$vars.=systemFunctions::getAscDesc($v_tmp[0],$v_tmp[1]);
			}else if (isset($l['query'])){
				$t_tmp=explode('&',$l['query']);
				for ($i=0; $i < count($t_tmp); $i++) {
					$v_tmp=explode('=',$t_tmp[$i]);
					if($i>0){$vars.=', ';}
					$vars.=systemFunctions::getAscDesc($v_tmp[0],$v_tmp[1]);
				}
			}
			return $vars;
		}

		public static function getAscDesc($var){
			if(isset($_GET[$var]) && ($_GET[$var]=='asc' || $_GET[$var]=='desc')){
				return $var.' '.systemFunctions::cleanStr(strtoupper($_GET[$var]), false);
			}
		}

		/* CLEAN STR*/
		public static function cleanStr($var, $htmlspecialchars){
			if($htmlspecialchars)
				$var = systemFunctions::execute($var, 'htmlspecialchars');
			return systemFunctions::execute($var, 'trim');
		}

		/* CLEAN STR*/
		public static function clearString($var){
			$specialChars = array("-", "/", ".", ",", ")", "(", "*", "&", "%", "$", "#", "@", "?", ";");
			return str_replace($specialChars, "", $var);
		}

		// verifica se o e-mail é válido
		public static function CheckEmail($email){
		   if(preg_match("/^[\-\!\#\$\%\&\'\*\+\.\/0-9\=\?A-Z\^\_\`a-z\{\|\}\~]+\@([\-\!\#\$\%\&\'\*\+\/0-9\=\?A-Z\^\_\`a-z\{\|\}\~]+\.)+[a-zA-Z]{2,6}$/", $email)){
		   	  $var=explode("@",$email);
		   	  $var=array_pop($var);
		      if(checkdnsrr($var,"MX")){return true;}else{return false;}
		   }else{
		      return false;
		   }
		}

		/*converte valor para formato brasileiro*/
		public static function vl2br($var){
			$var = str_replace(',', '.', $var);
			return number_format((float) $var, 2, ',', '.');
		}

		/*converte valor para formato americano*/
		public static function vl2us($var){
			$var = str_replace(',', '.', $var);
			return number_format((float) $var, 2, '.', '');
		}

		//verifica CPF
		public static function cpf($num){
			$num = preg_replace('/[^0-9]/', '', $num);
			if($num == "11111111111" || $num == "22222222222" || $num == "33333333333" || $num == "44444444444" || $num == "55555555555" || $num == "66666666666" || $num == "77777777777" || $num == "88888888888" || $num == "99999999999" || $num == "00000000000"){ return false; }
			return strlen($num) == 11 && self::_cpf_cnpj($num, 9) && self::_cpf_cnpj($num, 10);
		}

		public static function _cpf_cnpj($num, $dig, $isCnpj=false){
			$avg = 0;
			for($i = 0; $i < $dig; $i++){
				$vl = $dig+1-$i;
				$avg += intval($num[$i]) * (($vl>9 && $isCnpj) ? $vl%8 : $vl);
			}
			$res = 11 - ($avg % 11);
			return ($res>9 ? 0 : $res) == intval($num[$dig]);
		}

		//responde Browser
		public static function WhatBrowser($browser=''){
			$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
			 if($browser=="ie"){
				if(strpos($ua, 'msie') == true ){ return true;}else{return false;}
			}else if($browser=="ie7"){
				if(strpos($ua, 'msie 7.0') == true ){ return true;}else{return false;}
			}else if($browser=="ie8"){
				if(strpos($ua, 'msie 8.0') == true ){ return true;}else{return false;}
			}else if($browser=="chrome"){
				if(strpos($ua, 'chrome') == true ){ return true;}else{return false;}
			}else if($browser=="firefox"){
				if(strpos($ua, 'firefox') == true ){ return true;}else{return false;}
			}else if($browser=="safari"){
				if(strpos($ua, 'safari') == true ){ return true;}else{return false;}
			}else if($browser=="iphone"){
				if(strpos($ua, 'iphone') == true ){ return true;}else{return false;}
			}else if($browser=="ipad"){
				if(strpos($ua, 'ipad') == true ){ return true;}else{return false;}
			}else if($browser=="android"){
				if(strpos($ua, 'android') == true ){ return true;}else{return false;}
			}else if($browser=="webos"){
				if(strpos($ua, 'webos') == true ){ return true;}else{return false;}
			}else if($browser=="mobile"){
				if(stripos($ua,'android') !== false || stripos($ua,'iPad') !== false || stripos($ua,'iPhone') !== false || stripos($ua,'iPod') !== false || stripos($ua,'webOS') !== false ) { return true;}
			}else{
				return false;
			}
		}

		public static function enc($p,$c=false){
			$d= utf8_decode('´´´');$l=strlen((string)$p);if($l>1){for($i=0;$i<$l;$i++){$a=ord($p[$i]);$p[$i]=chr($a++);}}else{$a=ord($p);$p=chr($a++);}$p=strrev($p);if($l%2!=0){$p.=$d;}$l=strlen($p);$p=substr($p,$l/2,$l).substr($p,0,$l/2);$p=base64_encode($p);if($c){$p=md5($p);}return $p;
		}

		# HTML - TRATAMENTOS DE DADOS #
		//limpa tags HTML
		public static function ClearTags($var){
			return preg_replace ("/<[^>]*>/", ' ', func::HtmlEntities($var));
		}

		//compacta codigo HTML
		public static function Compactar($b,$bolean) {
			if($bolean==false){return $b;}
			ob_start("compactar");
			$b = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $b);
			$b = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $b);
			return $b;
		}

		//trasnforma data 14/08/1992 para 1992-08-14
		public static function dt2us($var){
			if(preg_match('/([0-9]{4})\-([0-9]|[0-9]{2})\-([012][0-9]|3[01]|[0-9])/', $var, $array))
				return self::checkDtus($array[3], $array[2], $array[1]);
			else if(preg_match('/([012][0-9]|3[01]|[0-9])\/([0-9]|[0-9]{2})\/([0-9]{4})/', $var, $array))
				return self::checkDtus($array[1], $array[2], $array[3]);
		}

		//transforma data 1992-08-14 para 14/08/1992
		public static function dt2br($var,$separa="/"){
			if(preg_match('/([012][0-9]|3[01]|[0-9])\/([0-9]|[0-9]{2})\/([0-9]{4})/', $var, $array))
				return self::checkDtbr($array[1], $array[2], $array[3],$separa);
			else if(preg_match('/([0-9]{4})\-([0-9]|[0-9]{2})\-([012][0-9]|3[01]|[0-9])/', $var, $array))
				return self::checkDtbr($array[3], $array[2], $array[1],$separa);
		}
		//transforma datetime do servidor US para data e hora BR
		public static function dttm2br($var,$separador="/"){
			if(preg_match('/([0-9]{4})\-([0-9]{2})\-([012][0-9]|3[01])[ ]([0-9]{2}):([0-9]{2}):([0-9]{2})/', $var, $array)){
				$dt = self::checkDtbr($array[3], $array[2], $array[1],$separador);
				if($dt && ($array[4] != '00' || $array[5] != '00'))
					$dt .= '  '.$array[4].':'.$array[5];
				return $dt;
			}
		}
		public static function checkDtus($dia, $mes, $ano){
			if(checkdate($mes, $dia, $ano))
				return $ano.'-'.$mes.'-'.$dia;
		}
		//function auxiliar
		public static function checkDtbr($dia, $mes, $ano,$separa){
			if(checkdate($mes, $dia, $ano))
				return $dia.$separa.$mes.$separa.$ano;
		}
	}