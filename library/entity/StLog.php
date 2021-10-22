<?php
    namespace DwPhp\Library\entity;
    use DwPhp\Library\models\AbstractObject;
    use Doctrine\ORM\Mapping as ORM;
    
    /**
     * StLog
     *
     * @Table(name="st_log")})
     * @Entity
     */
    class StLog extends AbstractObject{
        
        protected $id;
        protected $relatedId;
        protected $idUser;
        protected $type;
        protected $nextValue;
        protected $tableName;
        protected $timeModification;
        protected $createLogs;
                     
    

        /**
         * Get the value of id
         */ 
        public function getId()
        {
            return $this->id;
        }

        /**
         * Set the value of id
         *
         * @return  self
         */ 
        public function setId($id)
        {
            $this->id = $id;

            return $this;
        }

        /**
         * Get the value of relatedId
         */ 
        public function getRelatedId()
        {
            return $this->relatedId;
        }

        /**
         * Set the value of relatedId
         *
         * @return  self
         */ 
        public function setRelatedId($relatedId)
        {
            $this->relatedId = $relatedId;

            return $this;
        }

        /**
         * Get the value of idUser
         */ 
        public function getIdUser()
        {
            return $this->idUser;
        }

        /**
         * Set the value of idUser
         *
         * @return  self
         */ 
        public function setIdUser($idUser)
        {
            $this->idUser = $idUser;

            return $this;
        }

        /**
         * Get the value of type
         */ 
        public function getType()
        {
            return $this->type;
        }

        /**
         * Set the value of type
         *
         * @return  self
         */ 
        public function setType($type)
        {
            $this->type = $type;

            return $this;
        }

        /**
         * Get the value of nextValue
         */ 
        public function getNextValue()
        {
            return $this->nextValue;
        }

        /**
         * Set the value of nextValue
         *
         * @return  self
         */ 
        public function setNextValue($nextValue)
        {
            $this->nextValue = $nextValue;

            return $this;
        }

        /**
         * Get the value of tableName
         */ 
        public function getTableName()
        {
            return $this->tableName;
        }

        /**
         * Set the value of tableName
         *
         * @return  self
         */ 
        public function setTableName($tableName)
        {
            $this->tableName = $tableName;

            return $this;
        }

        /**
         * Get the value of timeModification
         */ 
        public function getTimeModification()
        {
            return $this->timeModification;
        }

        /**
         * Set the value of timeModification
         *
         * @return  self
         */ 
        public function setTimeModification($timeModification)
        {
            $this->timeModification = $timeModification;

            return $this;
        }

        /**
         * Get the value of createLogs
         */ 
        public function getCreateLogs()
        {
            return $this->createLogs;
        }

        /**
         * Set the value of createLogs
         *
         * @return  self
         */ 
        public function setCreateLogs($createLogs)
        {
            $this->createLogs = $createLogs;

            return $this;
        }

        public function getNameTable(){
            return 'st_log';
        } 
    }   
       
?>