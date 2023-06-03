<?php
header("Content-Type:text/html; charset=utf-8");
$serverName = "GEORGE-SWIFT3\SQLEXPRESS";
$connectionInfo = array("Database" => "Volleyball", "UID" => "ck40292", "PWD" => "123", "CharacterSet" => "UTF-8");
$conn = sqlsrv_connect($serverName, $connectionInfo);
if ($conn) {
} else {
    echo "Connect Error!!!<br />";
    die(print_r(sqlsrv_errors(), true));
}
