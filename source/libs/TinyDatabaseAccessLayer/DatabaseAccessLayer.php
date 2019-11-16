<?php

namespace TinyDatabaseAccessLayer;

use PDO;

abstract class DatabaseAccessLayer {
    
    //Holds the PDO database connection object
    protected $dbconnection;
    
    //Holds the PDO ojbect
    protected $dbObject;
    
  
    
    //Class constructor trys to establish a connection.
    //Required to be called in classes extending. Hint parent::__construct();
    public function __construct() 
    {
        try {
            $this->dbconnection = $this->GetConnection();
        } 
        catch (PDOException $pdoException){
            
            $this->error = $pdoException->getMessage();
        }
    }
    
    
    //Database connection details.
    public static $Host = "127.0.0.1";
    public static $DatabaseName = "progressivweatherapp";
    public static $DatabaseUser = "weatherapp";
    public static $DatabasePassword = "aafjldj94sfd93dsjf"; 

    
    
    //Returns a PDO connection object.
    public function getConnection() 
    {

        $this->returnedconnection = null;

        //Try to establish a new connection.
        try {
            $this->returnedconnection = new \PDO(
                    "mysql:host=" .  self::$Host .
                    ";dbname=" .     self::$DatabaseName, 
                                     self::$DatabaseUser, 
                                     self::$DatabasePassword);

        //Catch the exception for error output.
        } catch (PDOException $exception) {
            echo "Opps " . $exception->getMessage();
        }

        return $this->returnedconnection;
    }
  

    //Now the connection is out the way these methods are required 
    //for inserting and retrieving classes
    
    public function query($query) {
        $this->dbObject = $this->dbconnection->prepare($query);
    }

    public function execute() {
        try {   
             return $this->dbObject->execute();
      } catch (PDOException $exception) {
            echo "Opps " . $exception->getMessage();
        }
    }

    //This is the binding method that will help prevent SQL injection.
    //The method takes the value and checks for data type if not stated in type.
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->dbObject->bindValue($param, $value, $type);
    }


}
