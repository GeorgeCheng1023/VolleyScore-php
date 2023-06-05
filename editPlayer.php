<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
</head>

<body id="wrapper">
  <?php include "components/header.php"; ?>

  <!-- Post update event -->
  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['playerID']) && isset($_POST['playerNumber']) && isset($_POST['playerName']) && isset($_POST['positions'])) {
      $playerID = $_POST['playerID'];
      $playerNumber = $_POST['playerNumber'];
      $playerName = $_POST['playerName'];
      $positions = $_POST['positions'];

      // Update player information
      $updatePlayerStmt = sqlsrv_prepare($conn, "UPDATE Player SET PlayerNumber = ?, PlayerName = ? WHERE PlayerID = ?", array(&$playerNumber, &$playerName, &$playerID));
      sqlsrv_execute($updatePlayerStmt);

      // Get current player positions
      $currentPositionsQuery = sqlsrv_query($conn, "SELECT PositionID FROM PlayerPosition WHERE PlayerID = $playerID");
      $currentPositions = array();
      while ($row = sqlsrv_fetch_array($currentPositionsQuery, SQLSRV_FETCH_ASSOC)) {
        $currentPositions[] = $row['PositionID'];
      }

      // Check for newly added positions and insert them into PlayerPosition table
      foreach ($positions as $position) {
        if (!in_array($position, $currentPositions)) {
          $insertPlayerPositionStmt = sqlsrv_prepare($conn, "INSERT INTO PlayerPosition (PlayerID, PositionID) VALUES (?, ?)", array(&$playerID, &$position));
          sqlsrv_execute($insertPlayerPositionStmt);
        }
      }

      // Check for removed positions and delete related data in PlayByPlay, GamePlayerPosition, and PlayerPosition tables
      foreach ($currentPositions as $currentPosition) {
        if (!in_array($currentPosition, $positions)) {
          // Delete related data in PlayByPlay table
          $deletePlayByPlayStmt = sqlsrv_prepare($conn, "DELETE FROM PlayByPlay WHERE GamePlayerPositionID IN (
              SELECT GPP.GamePlayerPositionID
              FROM GamePlayerPosition GPP
              INNER JOIN PlayerPosition PP ON GPP.PlayerPositionID = PP.PlayerPositionID
              WHERE PP.PlayerID = ? AND PP.PositionID = ?
          )", array(&$playerID, &$currentPosition));
          sqlsrv_execute($deletePlayByPlayStmt);

          // Delete related data in GamePlayerPosition table
          $deleteGamePlayerPositionStmt = sqlsrv_prepare($conn, "DELETE FROM GamePlayerPosition WHERE PlayerPositionID IN (
              SELECT PlayerPositionID
              FROM PlayerPosition
              WHERE PlayerID = ? AND PositionID = ?
          )", array(&$playerID, &$currentPosition));
          sqlsrv_execute($deleteGamePlayerPositionStmt);

          // Delete position from PlayerPosition table
          $deletePlayerPositionStmt = sqlsrv_prepare($conn, "DELETE FROM PlayerPosition WHERE PlayerID = ? AND PositionID = ?", array(&$playerID, &$currentPosition));
          sqlsrv_execute($deletePlayerPositionStmt);
        }
      }

      header("Location: player.php");
      exit();
    }
  }
  ?>


  <?php
  if (isset($_GET['playerID'])) {
    $playerID = $_GET['playerID'];

    // Retrieve player information
    $playerQuery = sqlsrv_query($conn, "SELECT * FROM Player WHERE PlayerID = $playerID");
    $player = sqlsrv_fetch_array($playerQuery, SQLSRV_FETCH_ASSOC);

    // Retrieve player's positions
    $playerPositionsQuery = sqlsrv_query($conn, "SELECT PositionID FROM PlayerPosition WHERE PlayerID = $playerID");
    $selectedPositions = array();
    while ($row = sqlsrv_fetch_array($playerPositionsQuery, SQLSRV_FETCH_ASSOC)) {
      $selectedPositions[] = $row['PositionID'];
    }

    // Retrieve all positions from Position table
    $positionsQuery = sqlsrv_query($conn, "SELECT * FROM Position");
    $positions = array();
    while ($row = sqlsrv_fetch_array($positionsQuery, SQLSRV_FETCH_ASSOC)) {
      $positions[] = $row;
    }
  ?>

    <div class="container">
      <h2>編輯球員</h2>
      <form action="" method="post">
        <input type="hidden" name="playerID" value="<?php echo $player['PlayerID']; ?>">
        <div class="form-group">
          <label for="playerNumber">球員號碼:</label>
          <input type="text" class="form-control" id="playerNumber" name="playerNumber" value="<?php echo $player['PlayerNumber']; ?>">
        </div>
        <div class="form-group">
          <label for="playerName">球員姓名:</label>
          <input type="text" class="form-control" id="playerName" name="playerName" value="<?php echo $player['PlayerName']; ?>">
        </div>
        <div class="form-group">
          <label for="positions">球員位置:</label>
          <?php
          foreach ($positions as $position) {
            $positionID = $position['PositionID'];
            $positionName = $position['PositionName'];
            $checked = in_array($positionID, $selectedPositions) ? 'checked' : '';

            echo '<div class="form-check">';
            echo '<label class="form-check-label"><input class="form-check-input" type="checkbox" name="positions[]" value="' . $positionID . '" ' . $checked . '>' . $positionName . '</label>';
            echo '</div >';
          }
          ?>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
      </form>
    </div>
    <?php
    include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
    ?>



  <?php
  } else {
    echo 'Player ID not provided.';
  }
  ?>