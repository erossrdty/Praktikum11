<?php

require './db.php';

$query = "SELECT * FROM language ORDER BY name";
$sql = $db->query($query);
$data = [];

while ($row = $sql->fetch_assoc()) {
    array_push($data, $row);
}

header("Content-Type: application/json");
echo json_encode($data);
