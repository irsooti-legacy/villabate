<?php

/*	[POST]
**	reporter: 'Anonimo',
**	title: value,
**	lat: App.control.temp.lat, 
**	lng: App.control.temp.lng,
**	voti: [not needed, this is only for init]
*/


require_once("connection.php");

if (isset($_POST)) {

	$id = $conn->real_escape_string($_POST['id']);
	$voti = $conn->real_escape_string($_POST['voti']);


	$query = "UPDATE report SET voti=$voti WHERE id=$id";
	$conn->query($query);

	header('Content-type: text/javascript');

	echo json_encode(
		[
			"id" => $id,
			"voti" => $voti
		], JSON_NUMERIC_CHECK);
}

$conn->close();