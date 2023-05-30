<?php
header("Content-Type:text/html; charset=utf-8");


?>
<html>
<?php
header("Content-Type:text/html; charset=utf-8");


?>
<!-- saved from url=(0076)http://mepopedia.com/~web102-a/midterm/hw03_1015445024/graphic%20design.html -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>客服專區</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body id="wrapper-02">
 <?php include 'header.php'; ?>
    
<div id="contents">
<?php   if(!isset($_POST['name'])||!isset($_POST['phone'])||!isset($_POST['mail'])||!isset($_POST['comment'])){
		if(!isset($_POST['name'])){
		echo "姓名變數錯誤，請依照上課變數名稱命名(name)<br/>";
		}else{echo"";}
		if(!isset($_POST['phone'])){
		echo "電話變數錯誤，請依照上課變數名稱命名(phone)<br/>";
		}else{echo"";}
		if(!isset($_POST['mail'])){
		echo "信箱變數錯誤，請依照上課變數名稱命名(mail)<br/>";
		}else{echo"";}
		if(!isset($_POST['comment'])){
		echo "留言變數錯誤，請依照上課變數名稱命名(comment)<br/>";
		}else{echo"";}
		}
		
		else{
		if(empty($_POST['name'])||empty($_POST['phone'])||empty($_POST['mail'])||empty($_POST['comment'])){
		echo "你有資料未輸入";
		exit();
		}
		else{
	
		if(isset($_POST['name'])){
				echo '<h2>'.$_POST['name'].'您好!您已傳送一則訊息給客服，我們將會盡快幫你服務<br/></h2>';
				echo '<br/>';
		}
		
		else{
			echo "姓名變數錯誤，請依照上課變數名稱命名(name)";
		}
		
?>
 
       <div class="detail_box clearfix">
			 <p class="photo">
                  <img src="thanku.png" width="250" height="200" alt="謝謝你">
             </p>
             <p class="text">
				<?php
				include 'db_connect.php';
				if(isset($_POST['phone'])){
				echo '<font size=3>以下是您輸入的資料:<br/><br/>';
				echo '電話:';
				echo '<font color=#66B3FF>'.$_POST['phone'].'</font>';
				echo '<br/>';
			}
			else{
				echo "電話變數錯誤，請依照上課變數名稱命名(phone)</br>";
			}
			
			if(isset($_POST['mail'])){
				echo 'e-mail:';
				echo '<font color=#66B3FF>'.$_POST['mail'].'</font>';
				echo '<br/>';
			}
			else{
				echo "信箱變數錯誤，請依照上課變數名稱命名(mail)</br>";
			}
			
			if(isset($_POST['comment'])){
				echo '你的留言內容:';
				echo '</br>';
				echo '<font color=#66B3FF>'.$_POST['comment'].'</font>';
			}
			else{
				echo "留言變數錯誤，請依照上課變數名稱命名(comment)";
			}
		}
		}
		
		header("Content-Type:text/html; charset=utf-8");
		$name=$_POST['name'];
		$phone=$_POST['phone'];
		$mail=$_POST['mail'];
		$comment=$_POST['comment'];
		$sql="INSERT INTO dbo.comment(name,phone,mail,comment) VALUES('$name','$phone','$mail','$comment')";
		$qury=sqlsrv_query($conn,$sql)or die("sql error".sqlsrv_errors());
?>
			 
             </p>
       </div>


         
</div>


</body></html>


