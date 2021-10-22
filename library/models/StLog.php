<?php
namespace DwPhp\Library\models;

use DwPhp\Library\entity\StLog AS StLog_DB;
use DwPhp\Library\sql;
use DwPhp\Library\systemFunctions;

class StLog extends StLog_DB{

    // Parâmetro Type 
    // 1 - Create Instance
    // 2 - Update Instance
    // 3 - Delete Instance

    public function __construct($params = [], $createLogs = 0, $idUser = null){
        $this->setDbTable($this->getNameTable());
        $this->setCreateLogs($createLogs);
        $this->setIdUser($idUser);
        $this->setId($params['id']                  ?? null);
        $this->setType($params['type']              ?? null);
        $this->setIdUser($params['idUser']          ?? null);
        $this->setNextValue($params['previousValue'] ?? null);
        $this->setTableName($params['tableName']  ?? null);
        $this->setTimeModification($params['timeModification'] ?? null);
        $this->setRelatedId($params['relatedId'] ?? null);
    }
}