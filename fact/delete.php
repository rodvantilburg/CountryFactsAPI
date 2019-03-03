<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object file
include_once '../config/database.php';
include_once '../objects/fact.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare fact object
$fact = new Fact($db);
 
// get fact id
$data = json_decode(file_get_contents("php://input"));
 
// set fact id to be deleted
$fact->id = $data->id;
 
// delete the fact
if($fact->delete()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "Fact was deleted."));
}
 
// if unable to delete the fact
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to delete fact."));
}
?>