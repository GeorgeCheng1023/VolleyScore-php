<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
</head>

<body id="wrapper">
  <?php include "components/header.php"; ?>

  <div class="container">
    <h1>登入</h1>
    <form method="post" action="">
      <div class="form-group">
        <label for="teamID">隊伍ID:</label>
        <input type="text" class="form-control" id="teamID" name="teamID" required>
      </div>
      <button type="submit" class="btn btn-primary">登入</button>
    </form>
  </div>

  <?php
  include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
  ?>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include($_SERVER['DOCUMENT_ROOT'] . '/utils/db_connect.php');

    $teamID = $_POST["teamID"];
    // Check if the team exists in the database
    $query = "SELECT COUNT(*) AS Count FROM Team WHERE TeamID = ?";
    $params = array($teamID);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
      die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $teamCount = $row["Count"];

    // If team exists, redirect to index.php
    if ($teamCount > 0) {
      sqlsrv_free_stmt($stmt);
      sqlsrv_close($conn);
      header("Location: index.php?TeamID=" . $teamID);
      setcookie("teamID", $teamID, time() + 3600);
      exit;
    }

    // If team does not exist, insert into Team table and redirect to team.php
    $insertQuery = "INSERT INTO Team (Name) VALUES (?)";
    $insertParams = array("New Team");
    $insertStmt = sqlsrv_query($conn, $insertQuery, $insertParams);

    if ($insertStmt === false) {
      die(print_r(sqlsrv_errors(), true));
    }


    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

    setcookie("teamID", $teamID, time() + 3600);

    header("Location: team.php?TeamID=" . $_COOKIE("teamID"));
    exit;
  }
  ?>