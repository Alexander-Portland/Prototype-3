<?php

    session_start();

    $nameCheck = $_SESSION['username'];
    $passCheck = $_SESSION['password'];

    $con = mysqli_connect('localhost','root','');

    mysqli_select_db($con,'demo');

    $studentPick = "select student_id, forname, surname from studentdetails where student_username = '$nameCheck' && student_password = '$passCheck' ";


    $resultStudent = mysqli_query($con,$studentPick);
    $numStudent = mysqli_num_rows($resultStudent);

    $row = $resultStudent->fetch_assoc();
    $ID = $row['student_id'];
    $Fname = $row['forname'];
    $Lname = $row['surname'];

    $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");

    if($numStudent == 0){
        $_SESSION['username'] = "";
        header('location:index.php');
    }

    if(isset($_POST['btnSendQuestion'])){
        $sendName = $_POST['sendName'];
        $sendTitle = htmlspecialchars($_POST['questionTitle'],ENT_COMPAT);
        $sendQuestion = htmlspecialchars($_POST['sendQuestion'],ENT_COMPAT);
        $teacherQuery = "select teacher_id from teacherdetails where teacher_id = '$sendName'";
        $resultTeacher = mysqli_query($con,$teacherQuery);
        $numResultTeacher = mysqli_num_rows($resultTeacher);
        if($numResultTeacher == 0){
            $errorStatement = "The teacher you selected does not exist on the system";
        }
        else{
            $teacherSelect = $resultTeacher->fetch_assoc();
            $teacherID = $teacherSelect['teacher_id'];
            $stmt = $dbh->prepare("insert into messages values('',?,?,?,?,0,'')");
            $stmt->bindParam(1,$ID);
            $stmt->bindParam(2,$teacherID);
            $stmt->bindParam(3,$sendTitle);
            $stmt->bindParam(4,$sendQuestion);
            $stmt->execute();
            header('location:MessengerStudent.php');
        }
    }

    if(isset($_POST['btnDelete'])){
        $messageID = $_POST['messageDeleteID'];
        $messageFind = "select Message_ID from messages where Message_ID = '$messageID'";
        $resultClassFind = mysqli_query($con,$messageFind);
        $numDeleteResult = mysqli_num_rows($resultClassFind);
        if($numDeleteResult == 1){
            $postRemove = "update messages set Question_Answered = 2 where Message_ID = $messageID";
            $postRemoveQuery = mysqli_query($con,$postRemove);
            header('location:MessengerStudent.php');
        }
        else{
            header('location:MessengerStudent.php');
        }
    }
    
?>

<html>
header('location:MessengerStudent.php')
    <head> 
        <title>login</title>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="mystyle.css">
        <script src="pageInteraction.js"></script>
    </head>
    <main>
        <section class = "centerPosClass">
            <section class = "helpContent">
                <form action="MessengerStudent.php">
                        <button class= "expandButton button">Return</button>
                </form>
                <label class = "loginLabel">Login rejected</label>
                <p><?php echo $errorStatement ?></p>
            </section>
        </section>
    </main>
</html>