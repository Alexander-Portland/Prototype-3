<?php
//first connection to the database
$con = mysqli_connect('localhost','root','');

mysqli_select_db($con,'demo');

//The ID of the selected post is selected for document extraction
$id = isset($_GET['post_id'])? $_GET['post_id'] : "";

//the first query checks if the session username and password checkout with an existing student account
$studentPick = "select * from class_posts where post_id=$id";
$resultStudent = mysqli_query($con,$studentPick);
$row = $resultStudent->fetch_assoc();
//Header of the page that opens document is set to document type
header('Content-Type: '.$row['mine']);

//Outputted data of extracted document
echo $row['data'];

//end of connection to database
session_abort();

$numStudent = mysqli_num_rows($resultStudent);
?>