<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/fact.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare fact object
$fact = new Fact($db);
 
// get id of fact to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of fact to be edited
$fact->id = $data->id;
 
// set fact property values
$fact->comment = $data->comment;
$fact->link = $data->link;
$fact->description = $data->description;
$fact->countryid = $data->countryid;
 
// update the fact
if($fact->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "Fact was updated."));
}
 
// if unable to update the fact, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to update fact."));
}
?>