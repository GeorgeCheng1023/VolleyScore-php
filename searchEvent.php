<?php
header("Content-Type:text/html; charset=utf-8");


?>
<html>
<?php
header("Content-Type:text/html; charset=utf-8");


?>
<!-- saved from url=(0076)http://mepopedia.com/~web102-a/midterm/hw03_1015445024/graphic%20design.html -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>查詢</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body id="wrapper-02">
  <?php include 'header.php'; ?>
    
<div id="contents">
<?php   		
		include "north_connect.php";
		if($_POST['id']!=''){
        $name=$_POST['id'];
		$sql="SELECT CompanyName FROM dbo.Customers WHERE CustomerID='$name'";
		}
		else{
			$sql="SELECT * FROM  dbo.Customers";
		}

		$qury=sqlsrv_query($conn,$sql) or die("sql error".sqlsrv_errors());

		while($row=sqlsrv_fetch_array($qury)){
			echo $row['CompanyName'];
		}
?>
</div>


</body></html>


