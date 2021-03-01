<?php
$dbh = new PDO("mysql:host=localhost;dbname=demo","root","");
$id = isset($_GET['post_id'])? $_GET['post_id'] : "";
$strat = $dbh->prepare("select * from class_posts where post_id=$id");
$strat->bindParam(1,$id);
$strat->execute();
$row = $strat->fetch();
header('Content-Type: '.$row['mine']);
echo $row['data'];
session_abort();
?>