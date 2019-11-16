<?php


namespace TinyDatabaseAccessLayer;



class DatabaseInsert extends \TinyDatabaseAccessLayer\DatabaseAccessLayer
{
    
   
    function __construct($sql = null)
    {
        parent::__construct();
        $this->query($sql);
    }


    public function getLastInsertID() {
        return $this->dbConnectobject->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->dbConnectobject->beginTransaction();
    }


    public function endTransaction()
    {
        return $this->dbConnectobject->commit();
    }

    public function cancelTransaction()
    {
        return $this->dbConnectobject->rollBack();
    }

    
        
    
}