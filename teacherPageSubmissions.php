<?php

    session_start();

    $nameCheck = $_SESSION['username'];

    $con = mysqli_connect('localhost','root','');

    mysqli_select_db($con,'demo');

    $teacherPick = "select teacher_id, teacher_forname, teacher_surname from teacherdetails where teacher_username = '$nameCheck' ";

    $resultTeacher = mysqli_query($con,$teacherPick);
    $numStudent = mysqli_num_rows($resultTeacher);

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
            echo "<script type='text/javascript'>alert('Upload');</script>";

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
            $errorTitle = "Upload rejected";
            $errorStatement = "You are attempting to upload a post to a class that does not exist";
        }
        }

    if(isset($_POST['btnDelete'])){
        $postID = $_POST['classDeleteID'];
        $classFind = "select post_id from class_posts where post_id = '$postID'";
        $resultClassFind = mysqli_query($con,$classFind);
        $numDeleteResult = mysqli_num_rows($resultClassFind);
        if($numDeleteResult == 1){
            $postDelete = "delete from class_posts where post_id = '$postID'";
            $postDeleteQuery = mysqli_query($con,$postDelete);
        }
        else{
            $errorTitle = "Delete rejected";
            $errorStatement = "You are attempting to delete a post that does not exist";
        }
        
        }
        if(isset($_POST['search'])){
            $postID = $_POST['classUpdateID'];
            $classTitleSearch = htmlspecialchars($_POST['postNameSearch'],ENT_COMPAT);
            $classDescriptionSearch = htmlspecialchars($_POST['classDescriptionSearch'],ENT_COMPAT);
            $classFindSearch = "select post_id from class_posts where post_id = '$postID'";

            $resultClassFind = mysqli_query($con,$classFindSearch);
            $numClassResult = mysqli_num_rows($resultClassFind);
            if($numClassResult == 1){
                $rowPostFind = $resultClassFind->fetch_assoc();

                $postID = $rowPostFind['post_id'];
                $name = $_FILES['myfileUpdate']['name'];
                $type = $_FILES['myfileUpdate']['type'];

                $data = file_get_contents($_FILES['myfileUpdate']['tmp_name']);
              
                $update = $dbh->prepare("update class_posts set postTitle = ?, description = ?, name = ?, mine = ?, data = ? where post_id = ?");
                $update->bindParam(1,$classTitleSearch);
                $update->bindParam(2,$classDescriptionSearch);
                $update->bindParam(3,$name);
                $update->bindParam(4,$type);
                $update->bindParam(5,$data);
                $update->bindParam(6,$postID);
                $update->execute();   
                

            }
            else{
                echo "<script type='text/javascript'>alert('Update failed to process');</script>";
                $errorTitle = "Update rejected";
                $errorStatement = "You are attempting to update a post that does not exist";
        
            }
        }
       
?>

<html>
    <head>
        <title>Teacher Submission</title>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="mystyle.css">
        <style>
            <?php include 'mystyle.css'; ?>
        </style>
        <script src="pageInteraction.js"></script>
        </head>
    </head>
    <main>
        <section class = "centerPosClass">
            <section class = "helpContent">

            <form action="teacherPage.php">
                    <button class= "expandButton button">Retry</button>
                </form>
                <label class = "loginLabel"><?php echo $errorTitle ?></label>
                    <p><?php echo $errorStatement ?></p>
            </section>
        </section>
    </main>
</html>