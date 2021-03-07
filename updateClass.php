<?php
    session_start();


    $con = mysqli_connect('localhost','root','');
    
    mysqli_select_db($con,'demo');

    if(isset($_POST['search'])){
    $classID = $_POST['classUpdateID'];
    $classTitleSearch = htmlspecialchars($_POST['classUpdate'],ENT_COMPAT);
    $classDescriptionSearch = htmlspecialchars($_POST['classDescriptionUpdate'],ENT_COMPAT);
    $classFindSearch = "select class_id from classdetails where class_id = '$classID'";

    $resultClassFind = mysqli_query($con,$classFindSearch);
    $numClassResult = mysqli_num_rows($resultClassFind);
    if($numClassResult == 1){
        $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");
        $update = $dbh->prepare("update classdetails set class_id = ?, class_title = ?, description = ? where class_id = $classID");
        $update->bindParam(1,$classID);
        $update->bindParam(2,$classTitleSearch);
        $update->bindParam(3,$classDescriptionSearch);
        $update->execute();   
        header("Refresh:0; administration.php");
    }
    else{
        echo "<script type='text/javascript'>alert('Update failed to process');</script>";
        header("Refresh:0; administration.php");
    }
    }
    header("Refresh:0; administration.php");
?>