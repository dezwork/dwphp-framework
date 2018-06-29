<?php

/**
 * @Author: Cleberson Bieleski
 * @Date:   2017-12-23 04:54:45
 * @Last Modified by:   Cleber
 * @Last Modified time: 20-04-2018 10:06:19
 */

	namespace DwPhp\Library\models;
	use DwPhp\Library\sql;

	abstract class AbstractObject{

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

	        $params = array_filter($params_tmp, function($var){ return !is_null($var);} );

	        $db = new sql();
	        $db->setTest($debug);
	        $db->setTable($this->dbTable);
	        $db->setSet($params);
	        $db->Insert();
	        $this->id = $db->getInsertId();

	        return $this->id;
	    }

	    public function update($debug = false){

	        $params_tmp = get_object_vars($this);

	        if(isset($params_tmp['dbTable'])){ unset($params_tmp['dbTable']);}
	        if(isset($params_tmp['dateCreate'])){ unset($params_tmp['dateCreate']);}
	        if(isset($params_tmp['userCreate'])){ unset($params_tmp['userCreate']);}

			$params = array_filter($params_tmp, function($var){ return !is_null($var);} );

	        $db = new sql();
	        $db->setTest($debug);
	        $db->setTable($this->dbTable);
	        $db->setSet($params);
	        $db->setWhere(array('id' => $this->getId()));
	        $db->Update();

	        return $this->getId();
	    }

    	public function delete($id){
	        $db = new sql();
	        $db->setTable($this->dbTable);
	        $db->setWhere(array('id' => $id));
	        $db->Delete();

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
	}
?>