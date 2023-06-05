<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
</head>

<body id="wrapper">
  <?php include "components/header.php"; ?>

  <div class="container">
    <h1>球員分析</h1>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>姓名</th>
          <th>平均得分率</th>
          <th>平均失誤率</th>
          <th>平均犯規率</th>
          <th>平均參團率</th>
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
          while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $playerID = $row["PlayerID"];
            $playerName = $row["PlayerName"];

            // Calculate player's statistics
            $avgScoreRate = getPlayerAverageRate($conn, $playerID, '得分');
            $avgErrorRate = getPlayerAverageRate($conn, $playerID, '失誤');
            $avgFoulRate = getPlayerAverageRate($conn, $playerID, '犯規');
            $participationRate = getPlayerParticipationRate($conn, $playerID);

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

          $avgRate = $totalPlayByPlay > 0 ? round(($playerScore / $totalPlayByPlay) * 100, 2) . "%" : "N/A";

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

          $participationRate = $totalPlayByPlay > 0 ? round(($playerPlayByPlay / $totalPlayByPlay) * 100, 2) . "%" : "N/A";

          return $participationRate;
        }


        ?>

      </tbody>
    </table>

  </div>
  <?php
  include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
  ?>