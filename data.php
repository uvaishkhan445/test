<?php
$con = mysqli_connect("localhost", "root", "", "oyly");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
$query = mysqli_query($con, "select * from contact_us");
while ($row = mysqli_fetch_assoc($query)) {
  $data[] = $row;
}
echo json_encode(array('data' => $data));
