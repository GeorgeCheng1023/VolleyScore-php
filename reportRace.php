<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
</head>

<body id="wrapper">
  <?php include "components/header.php"; ?>

  <?php

  // Get the game ID from the URI
  $gameID = $_GET['gameID'];

  // Get the team ID from the cookie
  $teamID = $_COOKIE['teamID'];

  // Connect to the database

  // Fetch the score names from the Score table
  $scoreNames = array();
  $sql = "SELECT ScoreName FROM Score";
  $stmt = sqlsrv_query($conn, $sql);
  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $scoreNames[] = $row['ScoreName'];
  }

  // Generate the report
  $report = array();

  // Fetch the play-by-play data for the given game and team
  $sql = "SELECT PlayerName, ScoreName
  FROM PlayByPlay
  JOIN GamePlayerPosition ON PlayByPlay.GamePlayerPositionID = GamePlayerPosition.GamePlayerPositionID
JOIN PlayerPosition ON PlayerPosition.PlayerPositionID =GamePlayerPosition.PlayerPositionID
  JOIN Player ON PlayerPosition.PlayerID = Player.PlayerID
  JOIN Score ON PlayByPlay.ScoreID = Score.ScoreID
  WHERE GamePlayerPosition.GameID =?
  AND GamePlayerPosition.TeamID = ?";
  $params = array($gameID, $teamID);
  $stmt = sqlsrv_query($conn, $sql, $params);

  if ($stmt !== false) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
      $playerName = $row['PlayerName'];
      $scoreName = $row['ScoreName'];

      if (!isset($report[$playerName])) {
        $report[$playerName] = array_fill_keys($scoreNames, 0);
      }

      $report[$playerName][$scoreName]++;
    }
  } else {
    die(print_r(sqlsrv_errors(), true));
  }

  // Calculate the totals
  $totals = array_fill_keys($scoreNames, 0);
  foreach ($report as $playerData) {
    foreach ($playerData as $scoreName => $count) {
      $totals[$scoreName] += $count;
    }
  }

  // Output the report
  ?>
  <div class="container mt-4">
    <h1>Game Report <a href="recordRace.php?gameID=<?php echo $gameID ?>" class="btn btn-primary"'>編輯紀錄</a></h1>
    <table class="table <?php echo isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'true' ? 'table-dark' : ''; ?> table-striped">
      <thead>
        <tr>
          <th>Player Name</th>
          <?php foreach ($scoreNames as $scoreName) { ?>
            <th><?php echo $scoreName; ?></th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($report as $playerName => $playerData) { ?>
          <tr>
            <td><?php echo $playerName; ?></td>
            <?php foreach ($scoreNames as $scoreName) { ?>
              <td><?php echo $playerData[$scoreName]; ?></td>
            <?php } ?>
          </tr>
        <?php } ?>
        <tr>
          <td class="table-primary">Total</td>
          <?php foreach ($scoreNames as $scoreName) { ?>
            <td class="table-primary"><?php echo $totals[$scoreName]; ?></td>
          <?php } ?>
        </tr>
      </tbody>
    </table>
  </div>
  <?php
  include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
  ?>