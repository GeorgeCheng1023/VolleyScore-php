<?php
// Check if the gameID is provided in the URI
if (isset($_GET['gameID'])) {
  $gameID = $_GET['gameID'];

  include($_SERVER['DOCUMENT_ROOT'] . '/utils/db_connect.php');

  // Delete records from PlayByPlay table
  $deletePlayByPlayQuery = "DELETE FROM PlayByPlay WHERE GamePlayerPositionID IN
                             (SELECT GamePlayerPositionID FROM GamePlayerPosition WHERE GameID = ?)";
  $params = array($gameID);
  $deletePlayByPlayStmt = sqlsrv_query($conn, $deletePlayByPlayQuery, $params);

  if ($deletePlayByPlayStmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  // Delete records from GamePlayerPosition table
  $deleteGamePlayerPositionQuery = "DELETE FROM GamePlayerPosition WHERE GameID = ?";
  $deleteGamePlayerPositionStmt = sqlsrv_query($conn, $deleteGamePlayerPositionQuery, $params);

  if ($deleteGamePlayerPositionStmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  // Delete records from Game table
  $deleteGameQuery = "DELETE FROM Game WHERE GameID = ?";
  $deleteGameStmt = sqlsrv_query($conn, $deleteGameQuery, $params);

  if ($deleteGameStmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  // Redirect back to the game list page or any other desired location
  header("Location: race.php");
  exit();
}
