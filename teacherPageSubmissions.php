

<?php

    session_start();

    $nameCheck = $_SESSION['username'];

    $con = mysqli_connect('localhost','root','');

    mysqli_select_db($con,'demo');

    $teacherPick = "select teacher_id, teacher_forname, teacher_surname from teacherdetails where teacher_username = '$nameCheck' ";

    $resultTeacher = mysqli_query($con,$teacherPick);
    $numStudent = mysqli_num_rows($resultTeacher);

    $row = $resultTeacher->fetch_assoc();
    $ID = $row['teacher_id'];
    $Fname = $row['teacher_forname'];
    $Lname = $row['teacher_surname'];
    $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");

    if($numStudent == 0){
        $_SESSION['username'] = "";
        header('location:index.php');
    }

    if(isset($_POST['btn'])){
        $classId = $_POST['classAddID'];
        $postName = htmlspecialchars($_POST['postName'],ENT_COMPAT);
        $classDescription = htmlspecialchars($_POST['classDescription'],ENT_COMPAT);

        $classPick = "select class_id from classdetails where class_id = '$classId'";
        $resultClass = mysqli_query($con,$classPick);
        $numAddResult = mysqli_num_rows($resultClass);
        if($numAddResult == 1){
            $name = $_FILES['myfile']['name'];
            $type = $_FILES['myfile']['type'];

            $data = file_get_contents($_FILES['myfile']['tmp_name']);

            $stmt = $dbh->prepare("insert into class_posts values('',?,?,?,?,?,?)");
            $stmt->bindParam(1,$classId);
            $stmt->bindParam(2,$postName);
            $stmt->bindParam(3,$classDescription);
            $stmt->bindParam(4,$name);
            $stmt->bindParam(5,$type);
            $stmt->bindParam(6,$data);
            $stmt->execute();
            header('location:teacherPage.php');
        }
        else{
            echo "<script type='text/javascript'>alert('upload failed to process');</script>";
        }
        }
?>