<?php
//start of connection to database
$dbh = new PDO("mysql:host=localhost;dbname=demo","root","");

//The ID of the selected post is selected for document extraction
$id = isset($_GET['post_id'])? $_GET['post_id'] : "";

//Start of fetch query
$strat = $dbh->prepare("select * from class_posts where post_id=$id");
$strat->bindParam(1,$id);

//Query execution
$strat->execute();

//Results of query are extracted
$row = $strat->fetch();

//Header of the page that opens document is set to document type
header('Content-Type: '.$row['mine']);

//Outputted data of extracted document 
echo $row['data'];

//end of connection to database
session_abort();
?>