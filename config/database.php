<?php
class Database{
 
    // specify your own database credentials
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
		$config = parse_ini_file('database.ini');
        try{
            $this->conn = new PDO("mysql:host=" . $config['servername'] . ";dbname=" . $config['dbname'], $config['username'], $config['password']);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>