<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>

</head>


<body id="wrapper">
    <?php include "components\header.php"; ?>

    <?php

    $teamID =  $_COOKIE["teamID"];
    if (!isset($teamID)) {
        header('Location: login.php');
    }
    $sql = 'SELECT Name FROM Team WHERE TeamID = ?';
    $params = array($_COOKIE["teamID"]);
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);


    // post update team name
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $teamname = $_POST["TeamName"];
        $sql = "UPDATE Team SET Name= '$teamname' WHERE TeamID='$teamId' ";
        $query = sqlsrv_query($conn, $sql, $updateParams);
        if ($query === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Redirect back to the player list page
        header("Location: team.php");
        exit();
    }

    ?>

    <div class="container">
        <form action="" method="POST">
            <div class="form-group row mb-2 mt-4">
                <label for="TeamID" class="col-sm-2 col-form-label">隊伍編號</label>
                <div class="col-sm-10">
                    <input disabled type="text" name="TeamID" class="form-control disabled" id="TeamID" value="<?php echo $_COOKIE["teamID"] ?>">
                </div>
            </div>

            <div class="form-group row mb-2 mt-4">
                <label for="TeamName" class="col-sm-2 col-form-label">隊伍名稱</label>
                <div class="col-sm-10">
                    <input type="text" name="TeamName" class="form-control" id="TeamName" placeholder="Enter your team name" value="<?php echo $row['Name'] ?>">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">儲存</button>
                </div>
            </div>
        </form>


    </div>


    <?php
    include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/foot.php');
