<?php

/**
 * @Author: Cleberson Bieleski
 * @Date:   2017-12-23 04:54:45
 * @Last Modified by:   Cleberson Bieleski
 * @Last Modified time: 2018-01-10 12:50:47
 */

	namespace DwPhp\Library\models;
	use DwPhp\Library\sql;
	/**
		Date Created: 30.11.2017 - Cleberson Bieleski
		Date Updated: 04.12.2017 - Cleberson Bieleski
	*/
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

    	public function insert(){
    		$db = new sql();
	        $params = get_object_vars($this);
	        if(isset($params['dbTable'])){
	        	unset($params['dbTable']);
	        }
	        $db->setTable($this->dbTable);
	        $db->setSet($params);
	        //$db->setTest(true);
	        $db->Insert();

	    //    $query = $db->getLastQuery();
	    //    $this->saveLog('INSERT', $query);

	        $this->id = $db->getInsertId();
	        return $this->id;
	    }

    	public function update(){
	        $db = new sql();

	        $params = get_object_vars($this);
	        unset($params['dbTable']);

	        $params = array_diff($params, array(null));

	        $db->setTable($this->dbTable);
	        $db->setSet($params);
	        $db->setWhere(array('id' => $this->getId()));
	        $db->Update();

	        $query = $db->getLastQuery();

	    //    $this->saveLog('UPDATE', $query);

	        return $this->getId();
	    }

    	public function delete($id){
	        $db = new sql();

	        $db->setTable($this->dbTable);
	        $db->setWhere(array('id' => $id));
	        $db->Delete();

	        $query = $db->getLastQuery();

	    //    $this->saveLog('DELETE', $query);

	        return true;
	    }

	    //public function saveLog($action, $query){
	    //	$logParams = array('action' => $action, 'query' => str_replace("'", '"', $query), 'owner' => $this->dbTable, 'date' => date("Y-m-d H:i:s"));
	    //	if(isset($_SESSION['USER']['ID']) && !empty($_SESSION['USER']['ID']))
	    //		$logParams['idUser'] = $_SESSION['USER']['ID'];
	    //    $log = new Log();
	    //    $log->insert($logParams);
	    //}


	    public function setId($id){
	        $this->id = $id;

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