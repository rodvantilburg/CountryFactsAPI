<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/fact.php';
 
// utilities
$utilities = new Utilities();
 
// instantiate database and fact object
$database = new Database();
$db = $database->getConnection();
$CountryCode=isset($_GET["cc"]) ? $_GET["cc"] : "";
 
// initialize object
$fact = new Fact($db);
 
// query facts
$stmt = $fact->readPaging($from_record_num, $records_per_page,$CountryCode);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>-1){
 
    // facts array
    $facts_arr=array();
    $facts_arr["records"]=array();
    $facts_arr["paging"]=array();
 
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
 
    // include paging
    $total_rows=$fact->count();
    $page_url="{$home_url}fact/read_paging.php?";
    $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $facts_arr["paging"]=$paging;
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($facts_arr);
}
 
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user facts does not exist
    echo json_encode(
        array("message" => "No facts found.")
    );
}
?>