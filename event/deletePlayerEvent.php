<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
</head>

<body id="wrapper">
  <?php include "components/header.php"; ?>


  <?php
  if (isset($_GET['playerID'])) {
    $playerID = $_GET['playerID'];

    // 删除球员的职位信息
    $deletePlayerPositionStmt = sqlsrv_prepare($conn, "DELETE FROM PlayerPosition WHERE PlayerID = ?", array(&$playerID));
    sqlsrv_execute($deletePlayerPositionStmt);
    // 删除球员信息
    $deletePlayerStmt = sqlsrv_prepare($conn, "DELETE FROM Player WHERE PlayerID = ?", array(&$playerID));
    sqlsrv_execute($deletePlayerStmt);


    // 关闭数据库连接
    sqlsrv_close($conn);

    // 重定向回 players.php
    header("Location: /player.php");
    exit();
  }
  ?>

  <?php
  include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
  ?>