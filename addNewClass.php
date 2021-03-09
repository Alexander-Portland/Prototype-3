<?php
    session_start();


    $con = mysqli_connect('localhost','root','');
    
    mysqli_select_db($con,'demo');
                
    $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");

    if(isset($_POST['sendNewClass'])){
    $classTitle = htmlspecialchars($_POST['classNameInput'],ENT_COMPAT);
    $classDescription = htmlspecialchars($_POST['classDescriptionInput'],ENT_COMPAT);

    $classQuery = "select * from classdetails where class_title = '$classTitle'";
    $resultclass = mysqli_query($con,$classQuery);
    $numclass = mysqli_num_rows($resultclass);

    if($numclass >= 1){
        echo "<script type='text/javascript'>alert('Adding class aborted, you cannot add a class with the same title as a another class');</script>";
        header("Refresh:0; administration.php");
    }
    else{
        $stmt = $dbh->prepare("insert into classdetails values('',?,?)");
        $stmt->bindParam(1,$classTitle);
        $stmt->bindParam(2,$classDescription);
        $stmt->execute();

        header("Refresh:0; administration.php");
    }
    }
    header("Refresh:0; administration.php");
    
?>