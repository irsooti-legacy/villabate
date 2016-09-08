<?php
 require_once("connection.php"); $sql = "SELECT id, titolo, segnalatore, lat, lng, voti, img FROM report ORDER BY voti DESC"; $result = $conn->query($sql); $reports = []; echo "Debug ". $result->num_rows; if ($result->num_rows > 0) { while($row = $result->fetch_assoc()) { array_push($reports, [ "id" => $row["id"], "titolo" => $row["titolo"], "segnalatore" => $row["segnalatore"], "lat" => $row["lat"], "lng" => $row["lng"], "voti" => $row["voti"], "img" => $row["img"] ] ); } } session_start(); echo "var markers = ".json_encode($reports, JSON_NUMERIC_CHECK).";\r\n"; if(isset($_SESSION['user'])) { $id_user = $_SESSION['user']; $sql = "SELECT id_voto, voto FROM vote_memory WHERE id_user LIKE '$id_user'"; $result = $conn->query($sql); $count = $result->num_rows; $useful_obj = []; while ($fetched_result = $result->fetch_array(MYSQL_ASSOC)) { array_push($useful_obj, [ "id" => $fetched_result['id_voto'], "vote" => $fetched_result['voto'] ] ); } echo "var user_votes = {
    user: '".$_SESSION['name']."',
    id: '".$_SESSION['user']."',
    count: ".$count.",
    votes: ".json_encode($useful_obj, JSON_NUMERIC_CHECK)." }"; } $conn->close(); ?>
