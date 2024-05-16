<?php

header('Access-Control-Allow-Origin: *');

header('Content-Type: application/json');

header("Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept");
// Takes raw data from the request
$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = json_decode($json, true);
$base64Data = base64_decode($data['response']);
$reslt = json_decode($base64Data, true);
// print_r($reslt);
if ($reslt['success'] == 1) {
    echo json_encode(["data" => $reslt['data']]);
} else {
    echo json_encode(["status" => "400", "message" => "Payment Failed", "data" => $reslt]);
}