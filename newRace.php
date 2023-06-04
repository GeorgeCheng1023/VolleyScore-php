<?php
include($_SERVER['DOCUMENT_ROOT'] . '/utils/db_connect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve the form inputs
  $gameName = $_POST["gameName"];
  $dateTime = date("Y-m-d H:i:s", strtotime($_POST["dateTime"]));
  $opponentTeam = $_POST["opponentTeam"];
  $players = $_POST["players"];

  // Insert the game details into the Game table
  $sql = "INSERT INTO Game (Name, Time, TeamAID, TeamBID) VALUES (?, ?, ?, ?);
    SELECT SCOPE_IDENTITY() AS GameID";

  $params = array(&$gameName, &$dateTime, &$_COOKIE['teamID'], &$opponentTeam);
  $resource = sqlsrv_query($conn, $sql, $params);

  // Retrieve the newly inserted GameID
  sqlsrv_next_result($resource);
  sqlsrv_fetch($resource);
  $gameID = sqlsrv_get_field($resource, 0);

  // Insert the selected players into the GamePlayerPosition table
  $stmt = sqlsrv_prepare(
    $conn,
    "INSERT INTO GamePlayerPosition (GameID, TeamID, PlayerPositionID) VALUES (?, ?, ?)",
    array($gameID, $_COOKIE['teamID'], &$player)
  );
  foreach ($players as $player) {
    sqlsrv_execute($stmt);
  };

  header("Location: /race.php");
  exit();
}
?>



<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
</head>

<body id="wrapper">
  <?php include "components/header.php"; ?>

  <div class="container">
    <h2>Create Game</h2>
    <form action="" method="POST">
      <div class="form-group">
        <label for="gameName">Game Name</label>
        <input type="text" class="form-control" id="gameName" name="gameName" required>
      </div>
      <div class="form-group">
        <label for="dateTime">Date and Time</label>
        <input type="datetime-local" class="form-control" id="dateTime" name="dateTime" required>
      </div>
      <div class="form-group">
        <label for="opponentTeam">Opponent Team</label>
        <select class="form-control" id="opponentTeam" name="opponentTeam" required>
          <?php
          // Retrieve the TeamID from the cookie
          $teamID = $_COOKIE['teamID'];

          // Prepare the SQL query to fetch all teams except the own team
          $sql = "SELECT * FROM Team WHERE TeamID != ?";
          $params = array($teamID);
          $stmt = sqlsrv_query($conn, $sql, $params);

          // Display the teams as options in the select dropdown
          while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<option value='" . $row['TeamID'] . "'>" . $row['Name'] . "</option>";
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="players">Select Players</label><br>
        <?php
        // Prepare the SQL query to fetch players and their positions for the team
        $sql = "SELECT  p.PlayerName, pos.PositionName, pp.PlayerPositionID
                        FROM Player p
                        INNER JOIN PlayerPosition pp ON p.PlayerID = pp.PlayerID
                        INNER JOIN Position pos ON pp.PositionID = pos.PositionID
                        WHERE p.TeamID = ?";
        $stmt = sqlsrv_query($conn, $sql, array($teamID));

        // Display the players and their positions with checkboxes for player selection
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
          echo "<div class='form-check'>";
          echo "<input class='form-check-input' type='checkbox' id='player" . $row['PlayerPositionID'] . "' name='players[]' value='" . $row['PlayerPositionID'] . "'>";
          echo "<label class='form-check-label' for='player" . $row['PlayerPositionID'] . "'>" . $row['PlayerName'] . " - " . $row['PositionName'] . "</label>";
          echo "</div>";
        }
        ?>
      </div>
      <button type="submit" class="btn btn-primary">Create Game</button>
    </form>
  </div>


  <?php
  include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
  ?>