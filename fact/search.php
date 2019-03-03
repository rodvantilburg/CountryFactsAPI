<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/fact.php';
 
// instantiate database and fact object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$fact = new Fact($db);
 
// get keywords
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";
$CountryCode=isset($_GET["cc"]) ? $_GET["cc"] : "";

// query facts
$stmt = $fact->search($keywords,$CountryCode);
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // facts array
    $facts_arr=array();
    $facts_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $fact_item=array(
            "id" => $id,
            "comment" => $comment,
            "description" => html_entity_decode($description),
            "link" => $link,
            "countryid" => $countryid,
            "countryname" => $countryname
        );
 
        array_push($facts_arr["records"], $fact_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show facts data
    echo json_encode($facts_arr);
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no facts found
    echo json_encode(
        array("message" => "No facts found.")
    );
}
?>