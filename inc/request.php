<?php

require_once("connection.php");

$sql = "SELECT id, titolo, segnalatore, lat, lng, voti, img FROM report ORDER BY voti DESC";
$result = $conn->query($sql);

$reports = [];
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	array_push($reports, [
    		"id" =>				$row["id"],
    		"titolo" => 		$row["titolo"],
    		"segnalatore" => 	$row["segnalatore"],
    		"lat" =>  			$row["lat"],
    		"lng" =>  			$row["lng"],
    		"voti" =>  			$row["voti"],
    		"img" =>			$row["img"] ]
    		);
    }
} else {
    echo "0 results";
}

header('Content-type: text/javascript');
echo 'var markers = '.json_encode($reports, JSON_NUMERIC_CHECK);

$conn->close();
?>
