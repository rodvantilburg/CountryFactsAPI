<?php
// required header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/country.php';
 
// instantiate database and country object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$country = new Country($db);
 
// query countries
$stmt = $country->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $countries_arr=array();
    $countries_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $country_item=array(
            "id" => $id,
            "name" => $name
        );
 
        array_push($countries_arr["records"], $country_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show countries data in json format
    echo json_encode($countries_arr);
}
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no countries found
    echo json_encode(
        array("message" => "No countries found.")
    );
}
?>