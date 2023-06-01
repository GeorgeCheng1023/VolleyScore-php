<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
</head>

<body id="wrapper">
  <?php include "components/header.php"; ?>



  <div class="container">
    <div class="border p-3 mt-3">
      <h2>新增球員</h2>
      <hr>
      <form action="addPlayer.php" method="POST">
        <div class="form-group">
          <label for="playerName">球員號碼:</label>
          <input type="text" class="form-control mt-2 mb-2" id="playerNumber" name="playerNumber" required>
        </div>

        <div class="form-group">
          <label for="playerName">球員名稱:</label>
          <input type="text" class="form-control mt-2 mb-2" id="playerName" name="playerName" required>
        </div>
        <div class="form-group">
          <label for="positionID">職位:</label>
          <input type="number" class="form-control mt-2 mb-2" id="positionID" name="positionID" required>
        </div>
        <input type="hidden" name="teamID" value="<?php echo $teamID; ?>">
        <button type="submit" class="btn btn-success mt-2">新增</button>
      </form>
    </div>

    <div class="border p-3 mt-3">

      <h2>管理球員</h2>
      <hr>

      <?php
      include "db_connect.php";
      // Retrieve TeamID from URL parameter
      $teamID = $_GET['TeamID'];

      // Query to get players with position names based on TeamID
      $sql = "SELECT Player.PlayerNumber, Player.PlayerName, Position.PositionName 
             FROM Player 
             JOIN Position ON Player.PositionID = Position.PositionID 
             WHERE Player.TeamID = ?";

      // Prepare and execute the query
      $params = array($teamID);
      $stmt = sqlsrv_query($conn, $sql, $params);

      // Check if the query execution is successful
      if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
      }
      // Display players
      while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo '<div class="card mt-2">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $row['PlayerName'] . '</h5>';
        echo '<p class="card-text">職位: ' . $row['PositionName'] . '</p>';
        echo '<a href="edit_player.php?PlayerNumber=' . $row['PlayerNumber'] . '" class="btn btn-primary me-2">Edit</a>';
        echo '<a href="delete_player.php?PlayerNumber=' . $row['PlayerNumber'] . '" class="btn btn-danger">Delete</a>';
        echo '</div>';
        echo '</div>';
      }



      // Clean up resources
      sqlsrv_free_stmt($stmt);
      sqlsrv_close($conn);
      ?>
    </div>

    <hr>
  </div>



  <?php
  include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
  ?>