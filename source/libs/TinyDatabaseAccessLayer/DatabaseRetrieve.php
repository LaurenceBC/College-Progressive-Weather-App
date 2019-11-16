<?php

namespace TinyDatabaseAccessLayer;

use PDO;

class DatabaseRetrieve extends \TinyDatabaseAccessLayer\DatabaseAccessLayer
{
    
    function __construct($sql = null) 
    {
        parent::__construct();
        $this->query($sql);
    }
    
    
    public function resultSet() 
    {
        $this->execute();
        return $this->dbObject->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single() 
    {
        $this->execute();
      
        
          try {
            return $this->dbObject->fetch(PDO::FETCH_ASSOC);
        } 
        catch (PDOException $pdoException){
            
            $this->error = $pdoException->getMessage();
        }
       
    }

    public function rowCount() 
    {
        return $this->dbObject->rowCount();
    }
    
}