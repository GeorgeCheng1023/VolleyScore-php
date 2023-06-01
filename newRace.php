<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
</head>

<body id="wrapper">
  <?php include "components/header.php"; ?>



  <?php
  include($_SERVER['DOCUMENT_ROOT'] . '/utils/db_connect.php');

  // 確認是否從URI中獲取了團隊ID
  if (isset($_GET['TeamID'])) {
    $teamID = $_GET['TeamID'];
  } else {
    echo "Missing teamID in the URI.";
    exit();
  }

  // 獲取團隊數據
  $query = "SELECT * FROM Team";
  $result = sqlsrv_query($conn, $query);

  if ($result === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  $teams = array();
  while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $teams[$row['TeamID']] = $row['Name'];
  }

  // 獲取球員數據
  $query = "SELECT * FROM Player WHERE TeamID = ?";
  $params = array($teamID);
  $result = sqlsrv_query($conn, $query, $params);

  if ($result === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  $players = array();
  while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $players[$row['PlayerNumber']] = $row['PlayerName'];
  }

  // 提交表單處理
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 獲取表單數據
    $oppositID = $_POST['oppositID'];
    $playerNumbers = $_POST['playerNumber'];

    // 創建新的比賽
    $query = "INSERT INTO Game (Name, TeamAID, TeamBID) VALUES (?, ?, ?)";
    $params = array('New Game', $teamID, $oppositID);
    $result = sqlsrv_query($conn, $query, $params);

    if ($result === false) {
      die(print_r(sqlsrv_errors(), true));
    }

    // 獲取新插入的比賽ID
    $gameID = sqlsrv_query($conn, "SELECT SCOPE_IDENTITY()");
    $gameID = sqlsrv_fetch_array($gameID)['computed'];

    // 創建多個GamePlayerPosition行
    foreach ($playerNumbers as $playerNumber) {
      $query = "INSERT INTO GamePlayerPosition (GameID, TeamID, PlayerNumber) VALUES (?, ?, ?)";
      $params = array($gameID, $teamID, $playerNumber);
      $result = sqlsrv_query($conn, $query, $params);

      if ($result === false) {
        die(print_r(sqlsrv_errors(), true));
      }
    }

    // 提交成功後的重定向或其他操作
    echo "Game created successfully!";
    exit();
  }

  // 釋放資源
  sqlsrv_free_stmt($result);
  sqlsrv_close($conn);
  ?>

  <!DOCTYPE html>
  <html>

  <head>
    <title>Create Game</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  </head>

  <body>
    <div class="container">
      <h1>Create Game</h1>
      <form method="POST">
        <div class="form-group">
          <label for="oppositID">Opposite Team:</label>
          <select class="form-control" id="oppositID" name="oppositID">
            <?php foreach ($teams as $teamID => $teamName) : ?>
              <option value="<?php echo $teamID; ?>"><?php echo $teamName; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="playerNumber">Player Numbers:</label>
          <div id="playerNumbersContainer">
            <div class="input-group mb-3">
              <select class="form-control" name="playerNumber[]">
                <?php foreach ($players as $playerNumber => $playerName) : ?>
                  <option value="<?php echo $playerNumber; ?>"><?php echo $playerName; ?></option>
                <?php endforeach; ?>
              </select>
              <div class="input-group-append">
                <button class="btn btn-danger remove-player" type="button">Remove</button>
              </div>
            </div>
          </div>
          <button class="btn btn-primary add-player" type="button">Add Player</button>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
      </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
      $(document).ready(function() {
        // 添加球員表單項
        $('.add-player').click(function() {
          var playerItem = `
                    <div class="input-group mb-3">
                        <select class="form-control" name="playerNumber[]">
                            <?php foreach ($players as $playerNumber => $playerName) : ?>
                                <option value="<?php echo $playerNumber; ?>"><?php echo $playerName; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-danger remove-player" type="button">Remove</button>
                        </div>
                    </div>
                `;
          $('#playerNumbersContainer').append(playerItem);
        });

        // 刪除球員表單項
        $(document).on('click', '.remove-player', function() {
          $(this).parent().parent().remove();
        });
      });
    </script>
  </body>

  </html>

  <?php
  include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
  ?>