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

      // 更新球员信息
      $updatePlayerStmt = sqlsrv_prepare($conn, "UPDATE Player SET PlayerNumber = ?, PlayerName = ? WHERE PlayerID = ?", array(&$playerNumber, &$playerName, &$playerID));
      sqlsrv_execute($updatePlayerStmt);

      // 删除之前的职位信息
      $deletePlayerPositionStmt = sqlsrv_prepare($conn, "DELETE FROM PlayerPosition WHERE PlayerID = ?", array(&$playerID));
      sqlsrv_execute($deletePlayerPositionStmt);

      // 插入新的职位信息
      $insertPlayerPositionStmt = sqlsrv_prepare($conn, "INSERT INTO PlayerPosition (PlayerID, PositionID) VALUES (?, ?)", array(&$playerID, &$position));
      foreach ($positions as $position) {
        sqlsrv_execute($insertPlayerPositionStmt);
      }
      header("Location: /editPlayer.php?playerID=" . $playerID);

      exit();
    }
  }
  ?>


  <?php
  if (isset($_GET['playerID'])) {
    $playerID = $_GET['playerID'];

    // 根據 playerID 查詢球員信息
    $playerQuery = sqlsrv_query($conn, "SELECT * FROM Player WHERE PlayerID = $playerID");
    $player = sqlsrv_fetch_array($playerQuery, SQLSRV_FETCH_ASSOC);

    // 根據 playerID 查詢球員的職位
    $playerPositionsQuery = sqlsrv_query($conn, "SELECT PositionID FROM PlayerPosition WHERE PlayerID = $playerID");
    $selectedPositions = array();
    while ($row = sqlsrv_fetch_array($playerPositionsQuery, SQLSRV_FETCH_ASSOC)) {
      $selectedPositions[] = $row['PositionID'];
    }

    // 從 Position 表中獲取所有職位
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
          <label for="playerNumber">球員編號:</label>
          <input type="text" class="form-control" id="playerNumber" name="playerNumber" value="<?php echo $player['PlayerNumber']; ?>">
        </div>
        <div class="form-group">
          <label for="playerName">球員名稱:</label>
          <input type="text" class="form-control" id="playerName" name="playerName" value="<?php echo $player['PlayerName']; ?>">
        </div>
        <div class="form-group">
          <label for="positions">職位:</label>
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
        <button type="submit" class="btn btn-primary">更新</button>
      </form>
    </div>
    <?php
    include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
    ?>



  <?php
  } else {
    echo '未提供球員ID。';
  }
  ?>