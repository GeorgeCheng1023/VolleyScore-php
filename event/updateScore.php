<?php
// Retrieve the game ID, team, and action from the query parameters
$gameID = $_GET['gameID'];
$team = $_GET['team'];
$action = $_GET['action'];

include($_SERVER['DOCUMENT_ROOT'] . '/utils/db_connect.php');

// Retrieve the current scores from the database
$sql = "SELECT TeamAScore, TeamBScore FROM Game WHERE GameID = ?";
$params = array($gameID);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
  $teamAScore = $row['TeamAScore'];
  $teamBScore = $row['TeamBScore'];

  // Update the scores based on the action and team
  if ($team === "A") {
    if ($action === "increment") {
      $teamAScore++;
    } elseif ($action === "decrement") {
      $teamAScore--;
      // Ensure the score doesn't go below 0
      if ($teamAScore < 0) {
        $teamAScore = 0;
      }
    }
  } elseif ($team === "B") {
    if ($action === "increment") {
      $teamBScore++;
    } elseif ($action === "decrement") {
      $teamBScore--;
      // Ensure the score doesn't go below 0
      if ($teamBScore < 0) {
        $teamBScore = 0;
      }
    }
  }

  // Update the scores in the database
  $sql = "UPDATE Game SET TeamAScore = ?, TeamBScore = ? WHERE GameID = ?";
  $params = array($teamAScore, $teamBScore, $gameID);
  $stmt = sqlsrv_query($conn, $sql, $params);

  if ($stmt === false) {
    echo "Error updating scores: " . print_r(sqlsrv_errors(), true);
  } else {
    echo "Scores updated successfully!";
  }
} else {
  echo "Game not found.";
}

// Close the SQL Server connection
sqlsrv_close($conn);
