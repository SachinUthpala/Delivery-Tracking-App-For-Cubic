<?php

require_once '../Db.conn.php';
session_start();

$SelectSql = "SELECT * FROM `Users`";
$SelectSql_smtp = $conn->prepare($SelectSql);
$SelectSql_smtp->execute();

?>