<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
<style>
  .score {
    display: flex;
    gap: 20%;
    justify-content: center;
  }

  .score_block {
    position: relative;
    flex-grow: 1;
  }

  .score_vs {
    display: flex;
    align-items: end;
  }

  .score_name {
    font-size: 20px;
    position: absolute;
    top: 10px;
    left: 10px;
  }

  .score_button--inc {
    width: 100%;
    height: auto;
    font-size: 100px;
  }

  .score_button--dec {
    position: absolute;
    width: 50px;
    height: 50px;
    bottom: 0;
    right: 0;
  }
</style>

</head>

<body id="wrapper">
  <?php include "components/header.php"; ?>

  <div class="container">
    <?php
    // Retrieve the game ID from the URI
    $gameID = $_GET['gameID'];


    // Retrieve the game details including team scores
    $sql = "SELECT g.TeamAScore, g.TeamBScore, tA.Name AS TeamAName, tB.Name AS TeamBName, g.Time, g.Name
                FROM Game g
                INNER JOIN Team tA ON g.TeamAID = tA.TeamID
                INNER JOIN Team tB ON g.TeamBID = tB.TeamID
                WHERE g.GameID = ?";
    $params = array($gameID);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
      $teamAScore = $row['TeamAScore'];
      $teamBScore = $row['TeamBScore'];
      $teamAName = $row['TeamAName'];
      $teamBName = $row['TeamBName'];
      $gameName =  $row['Name'];
      $gameTime = $row['Time']->format('Y-m-d H:i:s');

      echo "<h2>比賽紀錄</h2>";
      echo "<h3> $gameName - $gameTime</h3>";
      echo "<div class='score'>";
      echo "<div class='score_block'>";
      echo "<button class='btn btn-primary score_button--inc' onclick='incrementScore(\"A\")'> <span class='score_name'>$teamAName </span> $teamAScore</button>";
      echo "<button class='btn btn-info score_button--dec' onclick='decrementScore(\"A\")'>-1</button>";
      echo "</div>";
      echo "<div class='fs-3 score_vs'>vs</div>";
      echo "<div class='score_block'>";
      echo "<button class='btn btn-danger score_button--inc' onclick='incrementScore(\"B\")'> <span class='score_name'>$teamBName </span>  $teamBScore</button>";
      echo "<button class='btn btn-warning score_button--dec' onclick='decrementScore(\"B\")'>-1</button>";
      echo "</div>";
      echo "</div>";
    } else {
      echo "<h2>Game not found.</h2>";
    }

    ?>
  </div>


  <div class="container">

    <?php
    // Retrieve the game ID from the URI
    $gameID = $_GET['gameID'];
    echo '<form action="/event/recordRaceEvent.php?gameID=' . $gameID . '" method="POST">';


    // Retrieve the participating players for the game
    $sql = "SELECT p.PlayerName, pos.PositionName, gpp.GamePlayerPositionID
  FROM GamePlayerPosition gpp
  INNER JOIN PlayerPosition as pp ON pp.PlayerPositionID = gpp.PlayerPositionID
  INNER JOIN Player p ON p.PlayerID = pp.PlayerID
  INNER JOIN Position pos ON pos.PositionID = pp.PositionID
  WHERE gpp.GameID = ?";
    $params = array($gameID);
    $stmt = sqlsrv_query($conn, $sql, $params);

    echo "<h4>Players:</h4>";
    echo "<div class='container'>";
    echo "<div class='row'>";
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
      $playerName = $row['PlayerName'];
      $positionName = $row['PositionName'];
      $gamePlayerPositionID = $row['GamePlayerPositionID'];

      echo "<div class='form-check col'>";
      echo "<input class='form-check-input' id='player-$gamePlayerPositionID'  required type='radio' name='gamePlayerPositionID' value='$gamePlayerPositionID'> ";
      echo "<label class='form-check-label' for='player-$gamePlayerPositionID' >$playerName - $positionName</label>";
      echo "</div>";
    };
    echo "</div></div>";


    // Retrieve the scores from the Score table
    $sql = "SELECT * FROM Score";
    $stmt = sqlsrv_query($conn, $sql);

    echo "<h4>Scores:</h4>";

    // Store the scores grouped by score type
    $scores = array();

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
      $scoreID = $row['ScoreID'];
      $scoreName = $row['ScoreName'];
      $scoreType = $row['ScoreType'];

      // Store each score in the corresponding score type group
      if (!isset($scores[$scoreType])) {
        $scores[$scoreType] = array();
      }

      $scores[$scoreType][] = array(
        'scoreID' => $scoreID,
        'scoreName' => $scoreName
      );
    }

    // Display the button group for each score type
    foreach ($scores as $scoreType => $scoreList) {
      // Set the button group color based on the score type
      $buttonColor = '';
      if ($scoreType == '得分') {
        $buttonColor = 'btn-primary';
      } elseif ($scoreType == '失誤') {
        $buttonColor = 'btn-warning';
      } else {
        $buttonColor = 'btn-danger';
      }

      echo "<div class='btn-group mb-2' role='group'>";
      echo "<button type='button' class='btn $buttonColor ' disabled>$scoreType</button>";

      foreach ($scoreList as $score) {
        $scoreID = $score['scoreID'];
        $scoreName = $score['scoreName'];

        echo "<button type='submit' name='scoreID' value='$scoreID' class='record-button btn $buttonColor'>$scoreName</button>";
      }

      echo "</div>";
    }

    echo "<br><br>";

    ?>
    <br>
    </form>
  </div>

  <div class="container">

    <?php
    // Retrieve the game ID from the query parameters
    $gameID = $_GET['gameID'];

    // Retrieve the play-by-play details based on the game ID
    $sql = "SELECT p.PlayerName, pos.PositionName, s.ScoreType, s.ScoreName, pbp.TeamAScore, pbp.TeamBScore
  FROM PlayByPlay pbp
  INNER JOIN GamePlayerPosition gpp ON gpp.GamePlayerPositionID = pbp.GamePlayerPositionID
  INNER JOIN PlayerPosition pp ON pp.PlayerPositionID = gpp.PlayerPositionID
  INNER JOIN Player p ON p.PlayerID = pp.PlayerID
  INNER JOIN Position pos ON pos.PositionID = pp.PositionID
  INNER JOIN Score s ON pbp.ScoreID = s.ScoreID
  WHERE gpp.GameID = ?";
    $params = array($gameID);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
      echo "Error retrieving play-by-play details: " . print_r(sqlsrv_errors(), true);
    } else {
      // Display the play-by-play details in a table
      echo "<table class='table table-bordered'>
            <thead>
              <tr>
                  <th>球員名稱</th>
                  <th>位置</th>
                  <th>得失分</th>
                  <th>得失分名稱</th>
                  <th>Team A Score</th>
                  <th>Team B Score</th>
              </tr>
            </thead>
            ";

      while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        echo "<tbody>";
        echo "<tr>";
        echo "<td>" . $row['PlayerName'] . "</td>";
        echo "<td>" . $row['PositionName'] . "</td>";
        echo "<td>" . $row['ScoreType'] . "</td>";
        echo "<td>" . $row['ScoreName'] . "</td>";
        echo "<td>" . $row['TeamAScore'] . "</td>";
        echo "<td>" . $row['TeamBScore'] . "</td>";
        echo "</tr>";
        echo "</tbody>";
      }

      echo "</table>";
    }

    ?>
  </div>

  </div>




  <script>
    function incrementScore(team) {
      fetch("event/updateScore.php?gameID=<?php echo $gameID; ?>&team=" + team + "&action=increment")
        .then(response => response.text())
        .then(result => {
          location.reload();
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }

    function decrementScore(team) {
      fetch("event/updateScore.php?gameID=<?php echo $gameID; ?>&team=" + team + "&action=decrement")
        .then(response => response.text())
        .then(result => {
          location.reload();
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }
  </script>
  <?php
  include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
  ?>