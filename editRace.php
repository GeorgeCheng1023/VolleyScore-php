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

      echo "<h2>編輯比賽</h2>";
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


  <?php
  // Retrieve the game ID from the URI
  $gameID = $_GET['gameID'];

  // Retrieve the scores from the Score table
  $sql = "SELECT * FROM Score";
  $stmt = sqlsrv_query($conn, $sql);

  echo "<h2>Play-by-Play Recording</h2>";

  // Display the score buttons
  echo "<h4>Scores:</h4>";
  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $scoreID = $row['ScoreID'];
    $scoreName = $row['ScoreName'];
    $scoreType = $row['ScoreType'];

    echo "<button class='record-button btn btn-success' onclick='recordPlay($scoreID)'>$scoreName</button>";
  }
  echo "<br><br>";

  // Retrieve the participating players for the game
  $sql = "SELECT p.PlayerID, p.PlayerName
                FROM Player p
                INNER JOIN GamePlayerPosition gpp ON p.PlayerID = gpp.PlayerID
                WHERE gpp.GameID = ?";
  $params = array($gameID);
  $stmt = sqlsrv_query($conn, $sql, $params);

  echo "<h4>Players:</h4>";
  echo "<form method='POST' action='record_play.php'>";
  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $playerID = $row['PlayerID'];
    $playerName = $row['PlayerName'];

    echo "<label>";
    echo "<input type='radio' name='playerID' value='$playerID'> $playerName";
    echo "</label><br>";
  }

  // Add hidden input fields for game ID and team ID
  echo "<input type='hidden' name='gameID' value='$gameID'>";
  echo "<input type='hidden' name='teamID' value='A'>";

  ?>
  <br>
  <button type='submit' class='btn btn-primary'>Record Play</button>
  </form>
  </div>

  <script>
    function recordPlay(scoreID) {
      const gameID = <?php echo $gameID; ?>;
      const teamID = 'A'; // Assuming team ID is 'A' for demonstration purposes

      // Send an AJAX request to record_play.php
      fetch('record_play.php?gameID=' + gameID + '&teamID=' + teamID + '&scoreID=' + scoreID)
        .then(response => response.text())
        .then(data => {
          console.log(data);
          // Reload the page after recording the play
          location.reload();
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }
  </script>




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