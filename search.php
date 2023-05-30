<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>DEMO</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body id="wrapper">
    <?php include 'header.php'; ?>

    <div id="contents">
        <form name="form" action="http://127.0.0.1/searchEvent.php" method="POST" accept-charset="UTF-8" align="center">
            <div class="detail_box clearfix">
                <div class="link_box">
                    <h3>查詢資料</h3>
                    <?php
                    include "north_connect.php";
                        $sql = "SELECT CustomerID , OrderID, EmployeeID, OrderDate FROM dbo.Orders";
                    $qury = sqlsrv_query($conn, $sql) or die("sql error" . sqlsrv_errors());

                    while ($row = sqlsrv_fetch_array($qury)) {
                        echo  $row['CustomerID'] . "," . $row['OrderID'] . "," . $row["EmployeeID"] . "," . $row['OrderDate']   -> format('Y-m-d') . "<br>";
                    }
                    ?>
                </div>
            </div>
        </form>
</body>

</html>