<?php
     header("Content-Type:text/html; charset=utf-8");
     $serverName="GEORGE-SWIFT3\SQLEXPRESS";
     $connectionInfo=array(
        "Database"=>"Volleyball",
        "UID"=>"sa",
        "PWD"=>"123",
        "CharacterSet" => "UTF-8",
        "TrustServerCertificate"=>false
    );
     $conn=sqlsrv_connect($serverName,$connectionInfo);
         if($conn){
             echo "Success!!!<br />";
         }else{
             echo "Error!!!<br />";
             die(print_r(sqlsrv_errors(),true));
         }
