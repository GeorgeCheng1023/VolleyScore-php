<?php
include($_SERVER['DOCUMENT_ROOT'] . '/utils/db_connect.php');


$playerNumber = $_GET['PlayerNumber'];

$sql = "DELETE FROM Player WHERE PlayerNumber = ? AND TeamID = ?";
$params = array($playerNumber, $teamID);
$stmt = sqlsrv_query($conn, $sql, $params);
// Check if the delete was successful
if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}

// Redirect back to the player list page
header("Location: /player.php?TeamID=" . $teamID);
sqlsrv_close($conn);
