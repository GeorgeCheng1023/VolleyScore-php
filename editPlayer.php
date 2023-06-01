<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
</head>

<body id="wrapper">
  <?php include "components/header.php"; ?>

  <div class="container">
    <h1>Edit Player</h1>
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
      <div class="form-group">
        <label for="playerName">Player Name:</label>
        <input type="text" class="form-control" id="playerName" name="playerName" value="<?php echo $player['PlayerName']; ?>" required>
      </div>
      <div class="form-group">
        <label for="positionID">Position ID:</label>
        <input type="number" class="form-control" id="positionID" name="positionID" value="<?php echo $player['PositionID']; ?>" required>
      </div>
      <input type="hidden" name="teamID" value="<?php echo $_GET["TeamID"] ?>">
      <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
  </div>
  <?php
  include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
  ?>