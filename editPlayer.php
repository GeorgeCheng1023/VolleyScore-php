<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
</head>

<body id="wrapper">
  <?php include "components/header.php"; ?>

  <div class="container">
    <h1>編輯隊員</h1>
    <hr>

    <?php
    include($_SERVER['DOCUMENT_ROOT'] . '/utils/db_connect.php');
    // Retrieve player number from URL parameter
    $playerNumber = $_GET['PlayerNumber'];
    $teamID = $_GET['TeamID'];

    // Retrieve player data from the database
    $sql = "SELECT PlayerNumber, PlayerName, PositionID FROM Player WHERE PlayerNumber = ? AND TeamID = ? ";
    $params = array($playerNumber, $teamID);
    $stmt = sqlsrv_query($conn, $sql, $params);

    // Check if the query execution is successful
    if ($stmt === false) {
      die(print_r(sqlsrv_errors(), true));
    }

    // Fetch the player data
    $player = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    // Check if the player exists
    if ($player === false) {
      die("Player not found.");
    }

    // Handle the form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Get the updated player name and position from the form
      $updatedPlayerName = $_POST['playerName'];
      $updatedPositionID = $_POST['positionID'];

      // Update the player's name and position in the database
      $updateSql = "UPDATE Player SET PlayerName = ?, PositionID = ? WHERE PlayerNumber = ? AND TeamID = ?";
      $updateParams = array($updatedPlayerName, $updatedPositionID, $playerNumber, $teamID);
      $updateStmt = sqlsrv_query($conn, $updateSql, $updateParams);

      // Check if the update was successful
      if ($updateStmt === false) {
        die(print_r(sqlsrv_errors(), true));
      }

      // Redirect back to the player list page
      header("Location: player.php?TeamID=" .  $teamID);
      exit();
    }
    ?>

    <!-- Display the player edit form -->
    <form action="" method="POST">
      <div class="form-group mt-2">
        <label for="playerName">名稱:</label>
        <input type="text" class="form-control mt-1" id="playerName" name="playerName" value="<?php echo $player['PlayerName']; ?>" required>
      </div>
      <div class="form-group mt-2">
        <label for="positionID">職位:</label>
        <select class="form-control form-select mt-1" id="positionID" name="positionID" required>
          <?php
          // Display position options
          include($_SERVER['DOCUMENT_ROOT'] . '/utils/db_connect.php');
          $positionSql = "SELECT PositionID, PositionName FROM Position";
          $positionStmt = sqlsrv_query($conn, $positionSql);
          // Check if the query execution is successful
          if ($positionStmt === false) {
            die(print_r(sqlsrv_errors(), true));
          }

          while ($position = sqlsrv_fetch_array($positionStmt, SQLSRV_FETCH_ASSOC)) {
            $selected = ($position['PositionID'] == $player['PositionID']) ? 'selected' : '';
            echo '<option value="' . $position['PositionID'] . '" ' . $selected . '>' . $position['PositionName'] . '</option>';
          }
          ?>
        </select>
      </div>
      <input type="hidden" name="teamID" value="<?php echo $_GET["TeamID"] ?>">
      <button type="submit" class="btn btn-primary mt-2">Save Changes</button>
    </form>
  </div>
  <?php
  include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
  ?>