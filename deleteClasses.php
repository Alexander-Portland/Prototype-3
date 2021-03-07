<?php
    session_start();


    $con = mysqli_connect('localhost','root','');
    
    mysqli_select_db($con,'demo');
                
    if(isset($_POST['btnDelete'])){
    $deleteID = $_POST['classDeleteID'];
    $deleteFind = "select class_id from classdetails where class_id = '$deleteID'";
    $resultClassFind = mysqli_query($con,$deleteFind);
    $numDeleteResult = mysqli_num_rows($resultClassFind);
    if($numDeleteResult == 1){
        $classDelete = "delete from classdetails where class_id = '$deleteID'";
        $classDeleteQuery = mysqli_query($con,$classDelete);
        header("Refresh:0; administration.php");
    }
    else{
        echo "<script type='text/javascript'>alert('Deletion failed to process');</script>";
        header("Refresh:0; administration.php");
    }
    }
    header("Refresh:0; administration.php");
?>