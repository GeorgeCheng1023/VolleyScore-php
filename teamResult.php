<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
</head>


<body id="wrapper">
  <?php include "components\header.php"; ?>
  <?php
  include 'utils/db_connect.php';
  $teamname = $_POST["TeamName"];
  $teamId = $_POST["TeamID"];
  $sql = "UPDATE dbo.Teams SET TeamName= '$teamname' WHERE TeamID='$teamId' ";
  $query = sqlsrv_query($conn, $sql) or die("sql error" . sqlsrv_errors());
  if (sqlsrv_rows_affected($query)) {
    $response = "更新成功";
  } else {
    $response = "錯誤";
  } ?>

  <div class="container">
    <div class="row mb-2 fs-1"><?php echo $response ?></div>
    <a href="/" role="button" class="btn btn-primary">按此返回首頁</a>
  </div>

  <?php
  include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
