<?php
class Country{
 
    // database connection and table name
    private $conn;
    private $table_name = "countries";
 
    // object properties
    public $id;
    public $name;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // used by select drop-down list
    public function readAll(){
        //select all data
        $query = "SELECT
                    id, name
                FROM
                    " . $this->table_name . "
                ORDER BY
                    name";
 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
 
        return $stmt;
    }
// used by select drop-down list
public function read(){
 
    //select all data
    $query = "SELECT
                id, name
            FROM
                " . $this->table_name . "
            ORDER BY
                name";
 
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
 
    return $stmt;
}	
function create(){

 
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                id=:id, name=:name";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
    $this->name=htmlspecialchars(strip_tags($this->name));
 
    // bind values
    $stmt->bindParam(":id", $this->id);
    $stmt->bindParam(":name", $this->name);
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
     
}	
}
?>