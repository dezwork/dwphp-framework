<?php

	namespace DwPhp\Library\models;
	use DwPhp\Library\sql;
	use DwPhp\Library\models\StLog;
	
	abstract class AbstractObject {

		public function __construct($params = null, $createLogs = 0, $idUser = null) {
			$this->setCreateLogs($createLogs);
			$this->setIdUser($idUser);
			$this->setDbTable($this->getNameTable());
			if ($params !== null) {
				$this->setCreateMethods($params);
			}
		}
		
		public function setCreateMethods($params = []) {
			foreach ($params as $property => $value) {
				if (is_int($property)) { continue; }
				$method = 'set' . ucfirst(strtolower(str_replace('_', '', $property)));
				if (method_exists($this, $method)){
					$this->$method($value);
				}
			}
		}

		public function getNum(){
	        $db = new sql();

	        $db->setTable($this->dbTable);
	        $db->setFields('id');
	        $db->Select();

	        return $db->getRecords();
	    }

    	public function getAll($order = '', $limit = '', $search = array()){
	        $db = new sql();

	        $db->setTable($this->dbTable);
	        $db->setFields('*');

	        if(!empty($order)){
	            $db->setOrderBy($order);
	        }
	        if(!empty($limit)){
	            $db->setLimit($limit);
	        }
	        if(count($search) > 0){
	            $where = $this->getSearchQuery($db->getWhere(), $search);
	            $db->setWhere($where);
	        }
	        $db->Select();
	        $child = get_called_class();

	        $return = array();
	        while($row = $db->getRow()){
	        	$return[] = new $child($row);
	        }

	        return $return;
	    }

    	public function getById($id){
	        $db = new sql();

	        $db->setTable($this->dbTable);
	        $db->setFields('*');
	        $db->setWhere(array('id' => $id));
	        $db->setLimit(1);
	        $db->Select();

	        $return = false;
	        if($db->getRecords() > 0){
	        	$row = $db->getRow();

	        	$child = get_called_class();

	        	$return = new $child($row);
	        }

	        return $return;
	    }

	    public function getSearchQuery($current, $search){
	    	$where = ($current != '' ? $current . ' AND ' : '');
            foreach($search as $i => $item){
                $where .= $i . ' LIKE "%' . $item . '%" ' . ($i < count($search)-1 ? ' OR ' : '');
            }

            return $where;
	    }

    	public function insert($debug = false){
			
	        $params_tmp = get_object_vars($this);
	        if(isset($params_tmp['dbTable'])){ unset($params_tmp['dbTable']);}
	        if(isset($params_tmp['dateUpdate'])){ unset($params_tmp['dateUpdate']);}
	        if(isset($params_tmp['userUpdate'])){ unset($params_tmp['userUpdate']);}
			if(isset($params_tmp['createLogs'])){ unset($params_tmp['createLogs']);}
			$idUser = $params_tmp['idUser'];
			if(get_called_class() != "DwPhp\Library\models\StLog"){
				if(isset($params_tmp['idUser'])){ unset($params_tmp['idUser']);}
			}
	        $params = array_filter($params_tmp, function($var){ return !is_null($var);} );

	        $db = new sql();
	        $db->setTest($debug);
	        $db->setTable($this->dbTable);
	        $db->setSet($params);
	        $db->Insert();

	        $this->id = $db->getInsertId();
			try{
				if($this->createLogs){
					$StLog = new StLog();
					$timeModification = str_replace('/','-',(new \util\DateTime())->dateTimeUS());
					$StLog->setRelatedId($this->id);
					$StLog->setType(1);
					$StLog->setIdUser($idUser);
					$StLog->setTimeModification($timeModification);
					$StLog->setTableName($this->dbTable);
					$StLog->setNextValue(json_encode(str_replace("'", "\'",$params)));
					$StLog->insert();
				}
			}
			catch(\Exception $e){
			}


	        return $this->id;
	    }

	    public function update($debug = false){

	        $params_tmp = get_object_vars($this);

	        if(isset($params_tmp['dbTable'])){ unset($params_tmp['dbTable']);}
	        if(isset($params_tmp['dateCreate'])){ unset($params_tmp['dateCreate']);}
	        if(isset($params_tmp['userCreate'])){ unset($params_tmp['userCreate']);}
			if(isset($params_tmp['createLogs'])){ unset($params_tmp['createLogs']);}
			$idUser = $params_tmp['idUser'];
			if(isset($params_tmp['idUser'])){ unset($params_tmp['idUser']);}

			$params = array_filter($params_tmp, function($var){ return !is_null($var);} );

	        $db = new sql();
	        $db->setTest($debug);
	        $db->setTable($this->dbTable);
	        $db->setSet($params);
	        $db->setWhere(array('id' => $this->getId()));
			
	        $db->Update();
			try{
				if($this->createLogs){
					$timeModification = str_replace('/','-',(new \util\DateTime())->dateTimeUS());
					$StLog = new StLog();
					$StLog->setRelatedId($this->id);
					$StLog->setType(2);
					$StLog->setIdUser($idUser);
					$StLog->setTableName($this->dbTable);
					$StLog->setTimeModification($timeModification);
					$StLog->setNextValue(json_encode(str_replace("'", "\'",$params)));
					$StLog->insert();
				}
			}
			catch(\Exception $e){
			}

	        return $this->getId();
	    }

    	public function delete($id=0){
	        $db = new sql();
			$db->setTable($this->dbTable);
			if((int)$id!=0){
	        	$db->setWhere(array('id' => $id));
				$idLog = $id;
			}else{
				$db->setWhere(array('id' => $this->getId()));
				$idLog = $this->getId();
			}
			$db->Delete();
			try{
				if($this->createLogs){
					$timeModification = str_replace('/','-',(new \util\DateTime())->dateTimeUS());
					$StLog = new StLog();
					$StLog->setRelatedId($idLog);
					$StLog->setType(3);
					$StLog->setIdUser($this->idUser);
					$StLog->setTimeModification($timeModification);
					$StLog->setTableName($this->dbTable);
					$StLog->setNextValue(' ');
					$StLog->insert();
				}
			}
			catch(\Exception $e){
			}

	        $query = $db->getLastQuery();

	        return true;
	    }

	    public function setId($id){
	        $this->id = (int)$id;

	        return $this;
	    }

	    public function getDbTable(){
	        return $this->dbTable;
	    }

	    public function setDbTable($dbTable){
	        $this->dbTable = $dbTable;

	        return $this;
		}

		public function setCreateLogs($createLogs){
	        $this->createLogs = $createLogs;

	        return $this;
		}

		public function setIdUser($idUser){
	        $this->idUser = $idUser;

	        return $this;
		}
		
		public function toArray() {
			return get_object_vars($this);
		}

		public function __toString() {
			return json_encode($this->toArray(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		}
	}
?>
