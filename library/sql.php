<?php

/**
 * @Author: Cleberson Bieleski
 * @Date:   2017-12-23 04:54:45
 * @Last Modified by:   Cleberson Bieleski
 * @Last Modified time: 2018-01-11 22:34:23
 */

namespace DwPhp\Library;

/*
	OBS: Ao definir o setTable, todos os valores setados na última consulta serão limpos.

	#### EXEMPLO DE INSERT
		$sql->setTable('nome_da_tabela');
		$sql->setSet(array('nome'=>'Bob','sobrenome'=>'Marley'));
		$sql->Insert();

	#### EXEMPLO DE UPDATE
		$sql->setTable('nome_da_tabela');
		$sql->setWhere(array('id'=>123));
		$sql->setSet(array('nome'=>'Bob','sobrenome'=>'Marley'));
		$sql->Update();

	#### EXEMPLO DE DELETE
		$sql->setTable('nome_da_tabela');
		$sql->setWhere(array('id'=>123));
		$sql->Delete();

	#### EXEMPLO DE SELECT
		$sql->setTable('nome_da_tabela');
		$sql->setFields('id,nome,sobrenome');
		$sql->setWhere(array('id'=>123));
		$sql->Select();

		while($row=$sql->getRow()){
			echo $row['id'];
		}
	#### EXIBINDO ULTIMO SQL
		$sql->getLastQuery();

*/
	class sql{

		const MYSQL_CONSTANTS = ['CURRENT_TIMESTAMP'];

		private $insertId;
		private $table;
		private $fields;
		private $set;
		private $limit;
		private $where;
		private $orderBy;
		private $groupBy;
		private $records;
		private $lastQuery;
		private $query;
		private $test;
		private $lastError;
		private $error;
		private $allError;
		private $debugger=false;



		//EXECUTA
		public function executeSQL($sql){
			//verifica se o banco de dados está conectado
			if(!isset($GLOBALS['CONN'])){ 	$this->setError('Não há conexão com o banco de dados <i><b>$</b>GLOBALS["CONN"]</i>'); }
			//se for teste, exibe SQL e não executa;
			if($this->getError()!=''){ echo "Em:".$sql.'<br/><br/>'.utf8_decode($this->getError()); exit; }
			//salva última SQL realizada
			$this->setlastQuery($sql);
			//se for teste, exibe SQL e não executa;
			if($this->getTest()==true){ echo $this->getlastQuery(); exit; }
			//Executa SQL usando ADODB
			if($this->getDebugger()==true){
				$GLOBALS['CONN']->debug = true;
			}

			$s=$GLOBALS['CONN']->Execute($sql);
			if($s===false){
				$erro=$GLOBALS['CONN']->ErrorMsg();
				$GLOBALS['f']->writeErrorSQL("[".date('d/m/Y H:m:i')."] - ".$erro);
				//armazenda erro em $lastQuery
				$this->setLastError($erro);
				//armazenda erro na lista de erro $allError do tipo ARRAY
				$this->setAllError($erro); 
				if($this->getDebugger()==true){
					//exit();
				}
			}else{
				//Armazenda a quantidade de resultados
				$this->setRecords($s->RecordCount());
				//salva na variavel query o objeto retornado do ADODB
				$this->setQuery($s);
				return $s;
			}
		}

		// SELECT
		public function Select($executar=true){
			$q='SELECT ';
			$q.=($this->getFields()!=''?$this->getFields():'*');
			$q.=' FROM ';
			if($this->getTable()!=''){
				$q.=$this->getTable();
			}else{
				$this->setError('É necessário atribuir um valor ao método <i><b>getTable</b></i>');
			}
			//insere WHERE
			if($this->getWhere()!=''){
				$q.=' WHERE '.$this->getWhere();
			}

			//insere WHERE
			if($this->getGroupBy()!=''){
				$q.=' GROUP BY '.$this->getGroupBy();
			}

			//insere ORDER BY
			if($this->getOrderBy()!=''){
				$q.=' ORDER BY '.$this->getOrderBy();
			}
			//insere LIMIT
			if($this->getLimit()!=''){
				$q.=' LIMIT '.$this->getLimit();
			}
			if($executar==false){
				return $q;
			}else{
	        	$this->executeSQL($q);
	    	}
		}

		// INSERIR
		public function Insert(){
			$q='INSERT INTO ';
			if($this->getTable()!=''){
				$q.=$this->getTable();
			}else{
				$this->setError('É necessário atribuir um valor ao método <i><b>getTable</b></i>');
			}
			//insere SET
			if($this->getSet()!=''){
				$q.=' SET ';
				$q.=$this->getSet();
			}else{
				$this->setError('É necessário atribuir um valor ao método <i><b>getSet</b></i>');
			}
			//insere WHERE
			if($this->getWhere()!=''){
				$q.=' WHERE '.$this->getWhere();
			}
	        $insert=$this->executeSQL($q);
	        //armazena ultimo id inserido no banco
	        if($this->getLastError()==''){
	        	$inserted=$GLOBALS['CONN']->Insert_ID();
	        	$this->setInsertId($inserted);
	    	}

		}

		// INSERIR
		public function Update($executar=true){
			$q='UPDATE ';
			if($this->getTable()!=''){
				$q.=$this->getTable();
			}else{
				$this->setError('É necessário atribuir um valor ao método <i><b>getTable</b></i>');
			}
			$q.=' SET ';
			//insere SET
			if($this->getSet()!=''){
				$q.=$this->getSet();
			}else{
				$this->setError('É necessário atribuir um valor ao método <i><b>getSet</b></i>');
			}
			//insere WHERE
			if($this->getWhere()!=''){
				$q.=' WHERE '.$this->getWhere();
			}else{
				$this->setError('É necessário atribuir um valor ao <i><b>getWhere</b></i>');
			}
			//insere LIMIT
			if($this->getLimit()!=''){
				$q.=' LIMIT '.$this->getLimit();
			}

			$q=str_replace(array('"NULL"',"'NULL'"), 'NULL', $q);

			if($executar==false){
				return $q;
			}else{
	        	return $this->executeSQL($q);
	    	}
		}

		// INSERIR
		public function Delete(){
			$q='DELETE FROM ';
			if($this->getTable()!=''){
				$q.=$this->getTable();
			}else{
				$this->setError('É necessário atribuir um valor ao método <i><b>getTable</b></i>');
			}
			//insere WHERE
			if($this->getWhere()!=''){
				$q.=' WHERE '.$this->getWhere();
			}
			//insere LIMIT
			if($this->getLimit()!=''){
				$q.=' LIMIT '.$this->getLimit();
			}
	        return $this->executeSQL($q);
		}



		//Limpa variaveis para novas consultas
		public function clear(){
			$this->setFields('');
			$this->setSet('');
			$this->setLimit('');
			$this->setWhere('');
			$this->setOrderBy('');
			$this->setGroupBy('');
			$this->setRecords('');
			$this->setQuery('');
		}

		//CONTROLE DE TABLE
		public function getRow(){
			if($GLOBALS['CONN']->_connectionID==false){
				return false;
			}
			$s=$this->getQuery();
			return $s->FetchRow();
		}
		//CONTROLE DE TABLE
		public function setInsertId($v=''){
			$this->insertId=$v;
		}
		//CONTROLE DE TABLE
		public function getInsertId(){
			return $this->insertId;
		}

		//CONTROLE DE TABLE
		public function setTable($v=''){
			$this->clear();
			$this->table=$v;
		}
		public function getTable(){
			return $this->table;
		}
		//CONTROLE DE COLUNAS
		public function setFields($v=''){
			$this->fields=$v;
		}
		public function getFields(){
			return $this->fields;
		}
		//CONTROLE DE SET
		public function setSet($v=''){
			if(is_array($v)){
	        		$q='';
	        		if(count($v)>0){
	        			foreach($v as $key=>$value){
			            	$q.= "`{$key}` = " . (in_array($value, self::MYSQL_CONSTANTS) ? $value : "'{$value}'") . ', ';
		        		}
		        		$q=substr($q, 0, -2);
		        	}
		        	$this->set=$q;
	        	}else{
	        		$this->set=$v;
	        	}
		}
		public function getSet(){
			return $this->set;
		}
		//CONTROLE DE LIMIT
		public function setLimit($v=''){
			$this->limit=$v;
		}
		public function getLimit(){
			return $this->limit;
		}
		//CONTROLE DE WHERE
		public function setWhere($v=''){
			if(is_array($v)){
	        		$q='';
	        		if(count($v)>0){
	        			foreach($v as $key=>$value){
			            	$q.= " {$key}= '{$value}' AND";
		        		}
		        		$q=substr($q, 0, -3);
		        	}
		        	$this->where=$q;
	        	}else{
	        		$this->where=$v;
	        	}
		}
		public function getWhere(){
			return $this->where;
		}
		//CONTROLE DE ORDER
		public function setOrderBy($v=''){
			$this->orderBy=$v;
		}
		public function getOrderBy(){
			return $this->orderBy;
		}
		//CONTROLE DE ORDER
		public function setGroupBy($v=''){
			$this->groupBy=$v;
		}
		public function getGroupBy(){
			return $this->groupBy;
		}
		//CONTROLE DE QUERY
		public function setRecords($v=''){
			$this->records=$v;
		}
		public function getRecords(){
			return $this->records;
		}
		//CONTROLE DE QUERY
		public function setlastQuery($v=''){
			$this->lastQuery=$v;
		}
		public function getlastQuery(){
			return $this->lastQuery;
		}
		//CONTROLE DE QUERY
		public function setQuery($v=''){
			$this->query=$v;
		}
		public function getQuery(){
			return $this->query;
		}
		//CONTROLE DE QUERY
		public function setTest($v=false){
			$this->test=$v;
		}
		public function getTest(){
			return $this->test;
		}

		//CONTROLE DE LAST ERROR
		public function setLastError($v=''){
			$this->lastError=$v;
		}
		public function getLastError(){
			return $this->lastError;
		}
		//CONTROLE DE LAST ERROR
		public function setDebugger($v=''){
			$this->debugger=$v;
		}
		public function getDebugger(){
			return $this->debugger;
		}
		//CONTROLE DE ALL ERROR
		public function setError($v=''){
			$tmp=array();
			$tmp=$this->getError('array');
			$tmp[]=$v;
			$this->error=$tmp;
		}
		public function getError($t='text'){
			$tmp='';
			if(isset($this->error) && count($this->error)>0){
				for ($i=0; $i < count($this->error); $i++) {
					$tmp.='Atenção: '.$this->error[$i].'<br/>';
				}
			}
			if($t=='array'){
				return $this->error;
			}else{
				return $tmp;
			}
		}
		//CONTROLE DE ALL ERROR
		public function setAllError($v=''){
			$tmp=array();
			$tmp=$this->getAllError('array');
			$tmp[]=$v;
			$this->allError=$tmp;
		}
		//RETORNA TODOS OS ERROS
		// parametro('text') - Retorna lista de erros
		// parametro('array') - Retorna tabela HTML de erros
		public function getAllError($t='text'){
			$tmp='';
			if(count($this->allError)>0){
				$tmp='<table>';
				for ($i=0; $i < count($this->allError); $i++) {
					$tmp.='<tr>';
					$tmp.='	<td width="100">#ERRO N&deg; '.($i+1).'</td>';
					$tmp.='	<td>SQL: '.strip_tags($this->getLastQuery()).'</td>';
					$tmp.='</tr>';
					$tmp.='<tr> <td></td><td><i>'.$this->allError[$i].'</i></td></tr>';
					$tmp.='<tr> <td colspan="2" height="15"></td></tr>';
				}
				$tmp.='</table>';
			}
			if($t=='array'){
				return $this->allError;
			}else{
				return $tmp;
			}
		}

	}