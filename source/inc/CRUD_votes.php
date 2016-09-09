<?php
/*	[POST]
**	user_id 
**	vote_id
**	voto
**/
require_once("connection.php");
session_start();

$user_id = $_SESSION['user'];
$vote_id = $conn->real_escape_string($_POST['id_vote']);
$voto = $conn->real_escape_string($_POST['vote']);


//$user_id = 22;
//$vote_id = 2;
//$voto = "up";

if ( ($voto == "up") || ($voto == "down") ) {


	$sql = "SELECT id, id_voto, voto FROM vote_memory WHERE 
		id_user LIKE '$user_id' AND
		id_voto LIKE '$vote_id'";

	$result = $conn->query($sql);

	if ($result) {

		$fetched_result = $result->fetch_array();
		$count = $result->num_rows;

		$id = $fetched_result['id'];
		$useful_obj = [ // index renamed 
		    "id" => $vote_id,
		    "vote" => $voto
		];

		if ($count == 1) { // EDIT

			$sql = "UPDATE vote_memory SET voto = '$voto' WHERE id = $id";
			$result = $conn->query($sql);

			$status = "Updated";

		}

		if ($count == 0) { // CREATE NEW
			$sql = "INSERT INTO vote_memory (id, id_voto, id_user, voto) 
			VALUES (NULL, '$vote_id', '$user_id', '$voto')";
			$result = $conn->query($sql);

			$status = "New";
		}

		if ($result) {
			header('Content-type: text/javascript');
			$response = [ "response" => $status, "data" => $useful_obj ];
			echo json_encode($response);
		}
	}
}