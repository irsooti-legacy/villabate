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
}

session_start();

echo "var markers = ".json_encode($reports, JSON_NUMERIC_CHECK).";\r\n";
//echo $_SESSION;
if(isset($_SESSION['user'])) {

$id_user = $_SESSION['user'];

// Find votes memory from DB

$sql = "SELECT id_voto, voto FROM vote_memory WHERE id_user LIKE '$id_user'";
$result = $conn->query($sql);
$count = $result->num_rows;

$useful_obj = [];

while ($fetched_result = $result->fetch_array()) {
    array_push($useful_obj, 
        [ // index renamed 
            "id" => $fetched_result['id_voto'],
            "vote" => $fetched_result['voto']
        ]
    );
}



echo "var user_votes = {
    user: '".$_SESSION['name']."',
    id: '".$_SESSION['user']."',
    count: ".$count.",
    votes: ".json_encode($useful_obj, JSON_NUMERIC_CHECK)." }";
}
$conn->close();
?>
