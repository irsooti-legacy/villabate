<?php

require_once("connection.php");
header('Content-type: text/javascript');
session_start();

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

	if (isset($_GET['JSON'])) {
		echo "{
		    user: '".$_SESSION['name']."',
		    id: '".$_SESSION['user']."',
		    count: ".$count.",
		    votes: ".json_encode($useful_obj, JSON_NUMERIC_CHECK)." }";
	}

	else {
		echo "var user_votes = {
		    user: '".$_SESSION['name']."',
		    id: '".$_SESSION['user']."',
		    count: ".$count.",
		    votes: ".json_encode($useful_obj, JSON_NUMERIC_CHECK)." }";
	}
}

$conn->close();