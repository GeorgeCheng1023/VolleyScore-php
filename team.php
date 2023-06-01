<?php
include($_SERVER['DOCUMENT_ROOT'] . '/pages/common/head.php');
?>
</head>


<body id="wrapper">
    <?php include "components\header.php"; ?>

    <div class="container">
        <form action="teamResult.php" method="POST">
            <div class="form-group row mb-2 mt-4">
                <label for="TeamID" class="col-sm-2 col-form-label">隊伍編號</label>
                <div class="col-sm-10">
                    <input type="text" name="TeamID" class="form-control" id="TeamID" placeholder="Enter your team id">
                </div>
            </div>

            <div class="form-group row mb-2 mt-4">
                <label for="TeamName" class="col-sm-2 col-form-label">隊伍名稱</label>
                <div class="col-sm-10">
                    <input type="text" name="TeamName" class="form-control" id="TeamName" placeholder="Enter your team name">
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
