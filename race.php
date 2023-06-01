<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
</head>

<body id="wrapper">
  <?php include "components/header.php"; ?>
  <div class="container">
    <h1>Team Games</h1>
    <?php echo "<a  href='newRace.php?TeamID=" . $_GET['TeamID'] . "' class='btn btn-primary'>新增比賽</a>" ?>
    <hr>

    <?php
    include($_SERVER['DOCUMENT_ROOT'] . '/utils/db_connect.php');

    // Retrieve team ID from URI parameter
    $teamID = $_GET['TeamID'];

    // Retrieve team name from the database
    $teamSql = "SELECT Name FROM Team WHERE TeamID = ?";
    $teamParams = array($teamID);
    $teamStmt = sqlsrv_query($conn, $teamSql, $teamParams);

    // Check if the query execution is successful
    if ($teamStmt === false) {
      die(print_r(sqlsrv_errors(), true));
    }

    // Fetch the team name
    $team = sqlsrv_fetch_array($teamStmt, SQLSRV_FETCH_ASSOC);

    // Check if the team exists
    if ($team === false) {
      die("Team not found.");
    }

    // Retrieve game details from the database
    $gameSql =
      "SELECT
        g.GameID,
        g.Name AS GameName,
        g.Time,
        CASE
          WHEN g.TeamAID = 1 THEN tB.Name
          WHEN g.TeamBID = 1 THEN tA.Name
        END AS OppositeTeamName,
        CASE
          WHEN g.TeamAID = 1 THEN g.TeamAScore
          WHEN g.TeamBID = 1 THEN g.TeamBScore
        END AS YourScore,
        CASE
          WHEN g.TeamAID = 1 THEN g.TeamBScore
          WHEN g.TeamBID = 1 THEN g.TeamAScore
        END AS OppositeScore
      FROM
        Game g
        INNER JOIN Team tA ON g.TeamAID = tA.TeamID
        INNER JOIN Team tB ON g.TeamBID = tB.TeamID
      WHERE
      g.TeamAID = ? OR g.TeamBID = ?;
      ";
    $gameParams = array($teamID, $teamID);
    $gameStmt = sqlsrv_query($conn, $gameSql, $gameParams);

    // Check if the query execution is successful
    if ($gameStmt === false) {
      die(print_r(sqlsrv_errors(), true));
    }
    ?>

    <!-- Display the team name and game details -->
    <h2><?php echo $team['Name']; ?>比賽</h2>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>比賽編號</th>
          <th>比賽名稱</th>
          <th>比賽時間</th>
          <th>敵方隊伍</th>
          <th>我方得分</th>
          <th>敵方得分</th>
          <th>勝敗</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Display game details
        while ($game = sqlsrv_fetch_array($gameStmt, SQLSRV_FETCH_ASSOC)) {
          echo '<tr>';
          echo '<td>' . $game['GameID'] . '</td>';
          echo '<td>' . $game['GameName'] . '</td>';
          echo '<td>' . $game['Time']->format('Y-m-d H:i:s') . '</td>';
          echo '<td>' . $game['OppositeTeamName'] . '</td>';
          echo '<td>' . $game['YourScore'] . '</td>';
          echo '<td>' . $game['OppositeScore'] . '</td>';
          echo '<td class="fw-bold text-' .  (($game['YourScore'] > $game['OppositeScore']) ? 'success' : 'danger') . '">' . (($game['YourScore'] > $game['OppositeScore']) ? 'Win' : 'Lose') .  "</td>";
          echo '</tr>';
        }
        ?>
      </tbody>
    </table>
  </div>


  <?php
  include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
  ?>