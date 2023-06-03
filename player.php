<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
</head>

<body id="wrapper">
  <?php include "components/header.php"; ?>

  <div class="container">
    <h2>新增球員</h2>
    <form action="event/addPlayerEvent.php" method="post">
      <div class="form-group">
        <label for="playerNumber">球員編號:</label>
        <input type="text" class="form-control" id="playerNumber" name="playerNumber">
      </div>
      <div class="form-group">
        <label for="playerName">球員名稱:</label>
        <input type="text" class="form-control" id="playerName" name="playerName">
      </div>
      <div class="form-group">
        <label for="positions">職位:</label>
        <?php

        // 從 Position 表中獲取所有職位
        $positionsQuery = sqlsrv_query($conn, "SELECT * FROM Position");
        while ($row = sqlsrv_fetch_array($positionsQuery, SQLSRV_FETCH_ASSOC)) {
          $positionID = $row['PositionID'];
          $positionName = $row['PositionName'];

          // 生成職位的 checkbox
          echo '<div class="checkbox">';
          echo '<label><input type="checkbox" name="positions[]" value="' . $positionID . '">' . $positionName . '</label>';
          echo '</div>';
        }
        ?>
      </div>
      <button type="submit" class="btn btn-primary">新增</button>
    </form>

    <hr>

    <h2>球員列表</h2>
    <?php
    $teamID = $_COOKIE["teamID"];
    $playersQuery = sqlsrv_query($conn, "SELECT * FROM Player WHERE TeamID = $teamID");

    echo '<table class="table">';
    echo '<tr><th>球員編號</th><th>球員名稱</th><th>職位</th><th>操作</th></tr>';

    while ($row = sqlsrv_fetch_array($playersQuery, SQLSRV_FETCH_ASSOC)) {
      $playerID = $row['PlayerID'];
      $playerNumber = $row['PlayerNumber'];
      $playerName = $row['PlayerName'];

      // 查詢球員的職位
      $playerPositionsQuery = sqlsrv_query($conn, "SELECT PositionName FROM Position INNER JOIN PlayerPosition ON Position.PositionID = PlayerPosition.PositionID WHERE PlayerPosition.PlayerID = $playerID");
      $positions = [];
      while ($positionRow = sqlsrv_fetch_array($playerPositionsQuery, SQLSRV_FETCH_ASSOC)) {
        $positions[] = $positionRow['PositionName'];
      }

      echo '<tr>';
      echo '<td>' . $playerNumber . '</td>';
      echo '<td>' . $playerName . '</td>';
      echo '<td>' . implode(', ', $positions) . '</td>';
      echo '<td><a href="edit_player.php?playerID=' . $playerID . '">編輯</a> | <a href="delete_player.php?playerID=' . $playerID . '">刪除</a></td>';
      echo '</tr>';
    }

    echo '</table>';


    ?>
  </div>
  <?php
  include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
  ?>