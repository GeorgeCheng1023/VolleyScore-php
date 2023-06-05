<?php
// Retrieve the game ID, team ID, score ID, and player ID from the query parameters
$gamePlayerPostionID = $_POST['gamePlayerPositionID'];
$scoreID = $_POST['scoreID'];
$gameID = $_GET['gameID'];

include($_SERVER['DOCUMENT_ROOT'] . '/utils/db_connect.php');

// Retrieve the current team scores from the Game table
$sql = "SELECT TeamAScore, TeamBScore FROM Game WHERE GameID = ?";
$params = array($gameID);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
  $teamAScore = $row['TeamAScore'];
  $teamBScore = $row['TeamBScore'];

  // Insert the play details into the PlayByPlay table
  $sql = "INSERT INTO PlayByPlay (ScoreID, GamePlayerPositionID, TeamAScore, TeamBScore) VALUES (?, ?, ?, ?)";
  $playID = uniqid(); // Generate a unique ID for the play
  $params = array($scoreID, $gamePlayerPostionID, $teamAScore, $teamBScore);
  $stmt = sqlsrv_query($conn, $sql, $params);

  if ($stmt === false) {
    echo "Error recording play: " . print_r(sqlsrv_errors(), true);
  } else {
    echo "Play recorded successfully!";
  }
} else {
  echo "Game not found.";
}

header('Location: /editRace.php?gameID=' . $gameID);

// Close the SQL Server connection
sqlsrv_close($conn);
