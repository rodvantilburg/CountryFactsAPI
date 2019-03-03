<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/fact.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
//echo "running random<br>";
// prepare fact object
$fact = new Fact($db);
 
$CountryCode=isset($_GET["cc"]) ? $_GET["cc"] : "";
 
// read the details of fact to be edited
$fact->random($CountryCode);
 if($fact->description!=null){
    // create array
    $fact_arr = array(
        "id" =>  $fact->id,
        "comment" => $fact->comment,
        "description" => $fact->description,
        "link" => $fact->link,
        "countryid" => $fact->countryid,
        "countryname" => $fact->countryname
 
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($fact_arr);
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user fact does not exist
    echo json_encode(array("message" => "Fact does not exist."));
}
?>