<?php
header("Access-Control-Allow-Origin: *"); // Allow any origin for CORS
header("Content-Type: application/json"); // Ensure correct content type
echo json_encode($_POST);
