<?php
class Fact{
 
    // database connection and table name
    private $conn;
    private $table_name = "facts";
 
    // object properties
    public $id;
    public $comment;
    public $description;
    public $link;
    public $countryid;
	public $countryname;
    public $created;
	public $query;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
// read products
function read($cc){
 
    // select all query
    $query = "SELECT
                c.name as countryname, f.id, f.comment, f.description, f.link, f.countryid, f.created
            FROM
                " . $this->table_name . " f
                LEFT JOIN
                    countries c
                        ON f.countryid = c.id";
	if (strlen($cc) === 2) {
		$query .= " where f.countryid = '".$cc."' ";
	}
	$query .= " ORDER BY f.created DESC";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}
// create product
function create(){

 
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                comment=:comment, link=:link, description=:description, countryid=:countryid, created=:created";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->comment=htmlspecialchars(strip_tags($this->comment));
    $this->link=htmlspecialchars(strip_tags($this->link));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->countryid=htmlspecialchars(strip_tags($this->countryid));
    $this->created=htmlspecialchars(strip_tags($this->created));
 
    // bind values
    $stmt->bindParam(":comment", $this->comment);
    $stmt->bindParam(":link", $this->link);
    $stmt->bindParam(":description", $this->description);
    $stmt->bindParam(":countryid", $this->countryid);
    $stmt->bindParam(":created", $this->created);
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
     
}
// used when filling up the update product form
function readOne(){

    // query to read single record
    $query = "SELECT
                c.name as countryname, f.id, f.comment, f.description, f.link, f.countryid, f.created
            FROM
                " . $this->table_name . " f
                LEFT JOIN
                    countries c
                        ON f.countryid = c.id
            WHERE
                f.id = ?
            LIMIT
                0,1";
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of product to be updated
    $stmt->bindParam(1, $this->id);
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->link = $row['link'];
    $this->comment = $row['comment'];
    $this->description = $row['description'];
    $this->countryid = $row['countryid'];
    $this->countryname = $row['countryname'];
}
function random($cc){

    // query to read single record
    $query = "SELECT
                c.name as countryname, f.id, f.comment, f.description, f.link, f.countryid, f.created
            FROM
                " . $this->table_name . " f
                LEFT JOIN
                    countries c
                        ON f.countryid = c.id";
	if (strlen($cc) === 2) {
		$query .= " where f.countryid = '".$cc."' ";
	}
	
	$query .= " ORDER BY RAND() LIMIT 1;";
 //echo "running random query<br>";
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of product to be updated
    //$stmt->bindParam(1, $this->id);
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->id = $row['id'];
    $this->link = $row['link'];
    $this->comment = $row['comment'];
    $this->description = $row['description'];
    $this->countryid = $row['countryid'];
    $this->countryname = $row['countryname'];
}
// update the product
function update(){
 
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                comment = :comment,
                link = :link,
                description = :description,
                countryid = :countryid
            WHERE
                id = :id";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->comment=htmlspecialchars(strip_tags($this->comment));
    $this->link=htmlspecialchars(strip_tags($this->link));
    $this->description=htmlspecialchars(strip_tags($this->description));
    $this->countryid=htmlspecialchars(strip_tags($this->countryid));
    $this->id=htmlspecialchars(strip_tags($this->id));
 
    // bind new values
    $stmt->bindParam(':comment', $this->comment);
    $stmt->bindParam(':link', $this->link);
    $stmt->bindParam(':description', $this->description);
    $stmt->bindParam(':countryid', $this->countryid);
    $stmt->bindParam(':id', $this->id);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}
// delete the product
function delete(){
 
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
 
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
     
}
// search products
function search($keywords,$cc){
 
    // select all query
    $query = "SELECT
                c.name as countryname, f.id, f.comment, f.description, f.link, f.countryid, f.created
            FROM
                " . $this->table_name . " f
                LEFT JOIN
                    countries c
                        ON f.countryid = c.id
            WHERE ";
	if (strlen($cc) === 2) {
		$query .= " f.countryid = '".$cc."' AND ";
	}
	$query .= "(f.comment LIKE ? OR f.description LIKE ? OR c.name LIKE ?)
            ORDER BY
                f.created DESC";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $keywords=htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";
 
    // bind
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);
    $stmt->bindParam(3, $keywords);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}
// read products with pagination
public function readPaging($from_record_num, $records_per_page,$cc){
 
    // select query
    $query = "SELECT
                c.name as countryname, f.id, f.comment, f.description, f.link, f.countryid, f.created
            FROM
                " . $this->table_name . " f
                LEFT JOIN
                    countries c
                        ON f.countryid = c.id ";
	if (strlen($cc) === 2) {
		$query .= "WHERE f.countryid = '".$cc."' ";
	}
            $query .= "ORDER BY f.created DESC
            LIMIT ?, ?";
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind variable values
    $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
 
    // execute query
    $stmt->execute();
 
    // return values from database
    return $stmt;
}
// used for paging products
public function count(){
    $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
 
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    return $row['total_rows'];
}

}
?>