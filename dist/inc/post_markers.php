<?php

/*	[POST]
**	reporter: 'Anonimo',
**	title: value,
**	lat: App.control.temp.lat, 
**	lng: App.control.temp.lng,
**	voti: [not needed, this is only for init]
*/

require_once("connection.php");
session_start();
if (isset($_SESSION['user'])) {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$titolo = $conn->real_escape_string($_POST['title']);
		$segnalatore = $conn->real_escape_string($_POST['reporter']);
		$lat = $conn->real_escape_string($_POST['lat']);
		$lng = $conn->real_escape_string($_POST['lng']);
		$voti = 0;


		$query = "INSERT INTO report VALUES (NULL, '$titolo', '$segnalatore', $lat, $lng, $voti, NULL)";
		$conn->query($query);

		header('Content-type: text/javascript');
		echo json_encode(
			[
				"id" => $conn->insert_id,
				"title" => $titolo,
				"reporter" => $segnalatore,
				"lat" => $lat,
				"lng" => $lng,
				"voti" => $voti
			], JSON_NUMERIC_CHECK);
	}
}
$conn->close();