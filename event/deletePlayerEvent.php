<?php
// Check if the playerID is provided in the GET request
if (isset($_GET['playerID'])) {
  $playerID = $_GET['playerID'];

  include($_SERVER['DOCUMENT_ROOT'] . '/utils/db_connect.php');

  // Delete related data in PlayByPlay table
  $deletePlayByPlayQuery = "DELETE FROM PlayByPlay WHERE GamePlayerPositionID IN (
        SELECT GPP.GamePlayerPositionID
        FROM GamePlayerPosition GPP
        WHERE GPP.PlayerPositionID IN (
            SELECT PP.PlayerPositionID
            FROM PlayerPosition PP
            WHERE PP.PlayerID = ?
        )
    )";
  $params = array($playerID);
  $stmt = sqlsrv_query($conn, $deletePlayByPlayQuery, $params);

  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  // Delete related data in GamePlayerPosition table
  $deleteGamePlayerPositionQuery = "DELETE FROM GamePlayerPosition WHERE PlayerPositionID IN (
        SELECT PlayerPositionID
        FROM PlayerPosition
        WHERE PlayerID = ?
    )";
  $params = array($playerID);
  $stmt = sqlsrv_query($conn, $deleteGamePlayerPositionQuery, $params);

  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  // Delete related data in PlayerPosition table
  $deletePlayerPositionQuery = "DELETE FROM PlayerPosition WHERE PlayerID = ?";
  $params = array($playerID);
  $stmt = sqlsrv_query($conn, $deletePlayerPositionQuery, $params);

  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  // Delete player from the Player table
  $deletePlayerQuery = "DELETE FROM Player WHERE PlayerID = ?";
  $params = array($playerID);
  $stmt = sqlsrv_query($conn, $deletePlayerQuery, $params);

  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  // Close the database connection
  sqlsrv_close($conn);
  header('Location: /player.php');
}
