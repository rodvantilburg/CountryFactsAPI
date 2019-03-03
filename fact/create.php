<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// get database connection
include_once '../config/database.php';
 
// instantiate fact object
include_once '../objects/fact.php';
 
$database = new Database();
$db = $database->getConnection();
 
$fact = new Fact($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 // make sure data is not empty
if(
    !empty($data->description) &&
    !empty($data->countryid)
){
 
    // set fact property values
    $fact->comment = $data->comment;
    $fact->link = $data->link;
    $fact->description = $data->description;
    $fact->countryid = $data->countryid;
    $fact->created = date('Y-m-d H:i:s');
 
    // create the fact
    if($fact->create()){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("message" => "Fact was created."));
    }
 
    // if unable to create the fact, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
        echo json_encode(array("message" => "Unable to create fact."));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create fact. Data is incomplete."));
}
?>