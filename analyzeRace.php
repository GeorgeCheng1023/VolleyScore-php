<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
</head>

<body id="wrapper">
  <?php include "components/header.php"; ?>

  <?php

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
  WHERE GamePlayerPosition.TeamID = ?";
  $params = array($teamID);
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
    <h1>比賽分析</h1>
    <canvas id="radarChart"></canvas>
    <table class="table <?php echo isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'true' ? 'table-dark' : ''; ?> table-striped">
      <thead>
        <tr>
          <th>姓名</th>
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

  <!-- Include Chart.js library -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    // Radar chart data
    var radarData = {
      labels: <?php echo json_encode($scoreNames); ?>,
      datasets: [{
          label: 'Total',
          data: <?php echo json_encode(array_values($totals)); ?>,
          // backgroundColor: 'rgba(0, 123, 255, 0.5)',
          // borderColor: 'rgba(0, 123, 255, 1)',
          borderWidth: 2
        },
        <?php foreach ($report as $playerName => $playerData) {
          echo '{';
          echo 'label: "' . $playerName . '",';
          echo 'data: [';
          foreach ($scoreNames as $scoreName) {
            echo $playerData[$scoreName] . ',';
          };
          echo '], borderWidth: 2';
          echo '},';
        } ?>
      ]
    };

    // Radar chart options
    var radarOptions = {
      responsive: true,
      aspectRatio: 2 / 1,
      // maintainAspectRatio: false,
      // scale: {
      //   ticks: {
      //     beginAtZero: true,
      //     stepSize: 1
      //   }
      // }
    };

    // Get the radar chart canvas element
    var radarChartCanvas = document.getElementById('radarChart');

    // Create the radar chart
    var radarChart = new Chart(radarChartCanvas, {
      type: 'radar',
      data: radarData,
      options: radarOptions,
    });
  </script>

  <?php
  include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
  ?>