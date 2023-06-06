<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
</head>

<body id="wrapper">
  <?php include "components/header.php"; ?>


  <div class="container">
    <h1>球員分析</h1>
    <canvas id="playerAnalysisChart"></canvas>

    <table class="table <?php echo isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'true' ? 'table-dark' : ''; ?> table-striped">
      <thead>
        <tr>
          <th>姓名</th>
          <th>平均得分率</th>
          <th>平均失誤率</th>
          <th>平均犯規率</th>
          <th>平均參與率</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $teamID = $_COOKIE['teamID'];
        if (!isset($teamID)) {
          header('Location: login.php');
        }
        // Get players and their analysis
        $query = "SELECT PlayerID, PlayerName FROM Player WHERE TeamID = ?";
        $params = array($teamID);
        $result = sqlsrv_query($conn, $query, $params);
        if ($result === false) {
          die(print_r(sqlsrv_errors(), true));
        }

        if (sqlsrv_has_rows($result)) {
          $players = array();
          $avgScoreRates = array();
          $avgErrorRates = array();
          $avgFoulRates = array();
          $participationRates = array();


          while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $playerID = $row["PlayerID"];
            $playerName = $row["PlayerName"];

            // Calculate player's statistics
            $avgScoreRate = getPlayerAverageRate($conn, $playerID, '得分');
            $avgErrorRate = getPlayerAverageRate($conn, $playerID, '失誤');
            $avgFoulRate = getPlayerAverageRate($conn, $playerID, '犯規');
            $participationRate = getPlayerParticipationRate($conn, $playerID);

            // Store player's data
            $players[] = $playerName;
            $avgScoreRates[] = $avgScoreRate;
            $avgErrorRates[] = $avgErrorRate;
            $avgFoulRates[] = $avgFoulRate;
            $participationRates[] = $participationRate;

            // Encode data as JSON
            $playerData = json_encode(array(
              'players' => $players,
              'avgScoreRates' => $avgScoreRates,
              'avgErrorRates' => $avgErrorRates,
              'avgFoulRates' => $avgFoulRates,
              'participationRates' => $participationRates,
            ));
            // Display player's analysis in table rows
            echo "<tr>";
            echo "<td>$playerName</td>";
            echo "<td>$avgScoreRate</td>";
            echo "<td>$avgErrorRate</td>";
            echo "<td>$avgFoulRate</td>";
            echo "<td>$participationRate</td>";
            echo "</tr>";
          }
        } else {
          $playerData = null;
          echo "<tr><td colspan='5'>No players found.</td></tr>";
        }

        // Function to calculate player's average rate for a specific score type
        function getPlayerAverageRate($conn, $playerID, $scoreType)
        {
          $query = "SELECT COUNT(*) AS TotalPlayByPlay FROM PlayByPlay";
          $result = sqlsrv_query($conn, $query);
          if ($result === false) {
            die(print_r(sqlsrv_errors(), true));
          }
          $totalPlayByPlay = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)["TotalPlayByPlay"];

          $query = "SELECT COUNT(*) AS PlayerScore FROM PlayByPlay 
        WHERE ScoreID IN (SELECT ScoreID FROM Score WHERE ScoreType = '$scoreType')
        AND GamePlayerPositionID IN 
        (SELECT GamePlayerPositionID
        FROM GamePlayerPosition 
        JOIN PlayerPosition ON PlayerPosition.PlayerPositionID = GamePlayerPosition.PlayerPositionID
        WHERE PlayerPosition.PlayerID = $playerID)";
          $result = sqlsrv_query($conn, $query);
          if ($result === false) {
            die(print_r(sqlsrv_errors(), true));
          }
          $playerScore = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)["PlayerScore"];

          $avgRate = $totalPlayByPlay > 0 ? round(($playerScore / $totalPlayByPlay) * 100, 2) : 0;

          return $avgRate;
        }

        // Function to calculate player's participation rate
        function getPlayerParticipationRate($conn, $playerID)
        {
          $query = "SELECT COUNT(*) AS TotalPlayByPlay FROM PlayByPlay";
          $result = sqlsrv_query($conn, $query);
          if ($result === false) {
            die(print_r(sqlsrv_errors(), true));
          }
          $totalPlayByPlay = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)["TotalPlayByPlay"];

          $query = "SELECT COUNT(*) AS PlayerPlayByPlay FROM PlayByPlay 
        WHERE GamePlayerPositionID IN (SELECT GamePlayerPositionID
        FROM GamePlayerPosition 
        JOIN PlayerPosition ON PlayerPosition.PlayerPositionID = GamePlayerPosition.PlayerPositionID
        WHERE PlayerPosition.PlayerID = $playerID)";
          $result = sqlsrv_query($conn, $query);
          if ($result === false) {
            die(print_r(sqlsrv_errors(), true));
          }
          $playerPlayByPlay = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)["PlayerPlayByPlay"];

          $participationRate = $totalPlayByPlay > 0 ? round(($playerPlayByPlay / $totalPlayByPlay) * 100, 2) : 0;

          return $participationRate;
        }


        ?>

      </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var playerData = <?php echo $playerData; ?>;
        if (playerData !== null) {
          var ctx = document.getElementById('playerAnalysisChart').getContext('2d');

          var chart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: playerData.players,
              datasets: [{
                  label: '平均得分率',
                  data: playerData.avgScoreRates,
                },
                {
                  label: '平均失誤率',
                  data: playerData.avgErrorRates,
                },
                {
                  label: '平均犯規率',
                  data: playerData.avgFoulRates,
                },
                {
                  label: '平均參與率',
                  data: playerData.participationRates,
                }
              ]
            },
            options: {
              scales: {
                x: {
                  beginAtZero: true,
                  maxBarThickness: 50
                },
                y: {
                  beginAtZero: true,
                  max: Math.max.apply(null, playerData.avgScoreRates.concat(playerData.avgErrorRates, playerData.avgFoulRates, playerData.participationRates)) + 10
                }
              }
            }
          });
        }
      });
    </script>
  </div>
  <?php
  include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
  ?>