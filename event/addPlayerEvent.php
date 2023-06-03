

  <?php
  include($_SERVER['DOCUMENT_ROOT'] . '/utils/db_connect.php');
  // 確保提交的表單數據存在
  if (isset($_POST['playerNumber']) && isset($_POST['playerName']) && isset($_POST['positions'])) {
    // 獲取表單數據
    $playerNumber = $_POST['playerNumber'];
    $playerName = $_POST['playerName'];
    $positions = $_POST['positions'];
    $teamID = $_COOKIE["teamID"];

    // 插入新球員的數據到 Player 表
    $query = "INSERT INTO Player (PlayerNumber, PlayerName, TeamID) VALUES (?, ?, ?); 
    SELECT SCOPE_IDENTITY() AS PlayerID";

    $params = array(&$playerNumber, &$playerName, &$teamID);

    $resource = sqlsrv_query($conn, $query, $params);
    sqlsrv_next_result($resource);
    sqlsrv_fetch($resource);
    $playerID = sqlsrv_get_field($resource, 0);

    // 將球員的職位插入到 PlayerPosition 表
    $insertPlayerPositionStmt = sqlsrv_prepare($conn, "INSERT INTO PlayerPosition (PlayerID, PositionID) VALUES (?, ?)", array(&$playerID, &$position));
    foreach ($positions as $position) {
      sqlsrv_execute($insertPlayerPositionStmt);
    }


    // 重定向回 players.php
    header("Location: /player.php");
    exit();
  }
  ?>

