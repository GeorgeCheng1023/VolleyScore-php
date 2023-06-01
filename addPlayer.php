<?php
include 'db_connect.php';
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the form data
  $playerNumber = $_POST["playerNumber"];
  $playerName = $_POST["playerName"];
  $positionID = $_POST["positionID"];
  $teamID = $_POST["teamID"];

  // Prepare the SQL statement to insert a new player
  $sql = "INSERT INTO Player (PlayerNumber, PlayerName, PositionID, TeamID) VALUES (?, ?, ?, ?)";

  // Prepare and execute the statement
  $params = array($playerNumber, $playerName, $positionID, $teamID);
  $stmt = sqlsrv_prepare($conn, $sql, $params);
  echo $stmt;

  // Check if the statement preparation is successful
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  // Execute the statement
  $result = sqlsrv_execute($stmt);

  // Check if the execution is successful
  if ($result === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  // Redirect back to the player management page
  header("Location: player.php?TeamID=" . $teamID);
}

// Close the database connection
sqlsrv_close($conn);
