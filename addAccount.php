<?php
    session_start();


    $con = mysqli_connect('localhost','root','');
    
    mysqli_select_db($con,'demo');
                
    $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");

    if(isset($_POST['accountAdd'])){
        $accountFName = htmlspecialchars($_POST['accountFirstName'],ENT_COMPAT);
        $accountLName = htmlspecialchars($_POST['accountLastName'],ENT_COMPAT);
        $accountUserName = htmlspecialchars($_POST['accountUserName'],ENT_COMPAT);
        $accountPassword = htmlspecialchars($_POST['accountPassword'],ENT_COMPAT);
        $accountType = htmlspecialchars($_POST['accountTypeSelect'],ENT_COMPAT);
        if($accountType == "Student"){
            $accountQuery = "select * from studentdetails where student_username = '$accountUserName'";
            $accountQueryResult = mysqli_query($con,$accountQuery);
            $numAccountQueryResult = mysqli_num_rows($accountQueryResult);
            if($numAccountQueryResult >= 1){
                echo "<script type='text/javascript'>alert('There is already an existing student account with that username');</script>";
            }
            else{
                $stmt = $dbh->prepare("insert into studentdetails values('',?,?,?,?)");
                $stmt->bindParam(1,$accountFName);
                $stmt->bindParam(2,$accountLName);
                $stmt->bindParam(3,$accountUserName);
                $stmt->bindParam(4,$accountPassword);
                $stmt->execute();
            }
        }
        elseif($accountType == "Teacher"){
            $accountQuery = "select * from teacherdetails where teacher_username = '$accountUserName'";
            $accountQueryResult = mysqli_query($con,$accountQuery);
            $numAccountQueryResult = mysqli_num_rows($accountQueryResult);
            if($numAccountQueryResult >= 1){
                echo "<script type='text/javascript'>alert('There is already an existing teacher account with that username');</script>";
            }
            else{
                $stmt = $dbh->prepare("insert into teacherdetails values('',?,?,?,?)");
                $stmt->bindParam(1,$accountFName);
                $stmt->bindParam(2,$accountLName);
                $stmt->bindParam(3,$accountUserName);
                $stmt->bindParam(4,$accountPassword);
                $stmt->execute();
            }
        }
        else{
            $accountQuery = "select * from admin where admin_username = '$accountUserName'";
            $accountQueryResult = mysqli_query($con,$accountQuery);
            $numAccountQueryResult = mysqli_num_rows($accountQueryResult);
            if($numAccountQueryResult >= 1){
                echo "<script type='text/javascript'>alert('There is already an existing admin account with that username');</script>";
            }
            else{
                $stmt = $dbh->prepare("insert into admin values('',?,?,?,?)");
                $stmt->bindParam(1,$accountFName);
                $stmt->bindParam(2,$accountLName);
                $stmt->bindParam(3,$accountUserName);
                $stmt->bindParam(4,$accountPassword);
                $stmt->execute();
            }
        }
    }
    header("Refresh:0; administration.php");
?>