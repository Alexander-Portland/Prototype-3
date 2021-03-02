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

if($numStudent == 0){
    $_SESSION['username'] = "";
    header('location:index.php');
}
?>

<html>
    <head>
    <title>Student Page</title>
    <link rel="stylesheet" href="mystyle.css">
    <style>
        <?php include 'mystyle.css'; ?>
    </style>

    <script src="pageInteraction.js"></script>

    </head>

    <body>
        <ul>
        <li><p class = "navBarTitle">Welcome <?php echo $Fname ?> <?php echo $Lname?></p></li>

        <li><a href="MessengerStudent.php">View Messages</a></li>

        <li><a href = "logout.php">Log Out</a></li>


        </ul>

        <?php
            $classPick = "select student_id, class_id from studentdetails_classdetails where student_id = '$ID'";
            $resultClass = mysqli_query($con,$classPick);
            $numClass = mysqli_num_rows($resultClass);


            while($rowClass = $resultClass->fetch_assoc()): ?>
                <?php
                    $sectionId = rand();
                    $classId = $rowClass["class_id"];
                    $classFind = "select class_id, class_title, description from classdetails where class_id = '$classId'";
                    $resultClassFind = mysqli_query($con,$classFind);
                    $numClassFind = mysqli_num_rows($resultClassFind);

                    $rowClassFind = $resultClassFind->fetch_assoc();
                    echo '<section class = "centerPosClass">';
                        echo '<Section id = "classDisplay" class="classPosts">';
                            echo '<button onclick="hideContent('.$sectionId.')" class="expandButton">Display</button>';

                            echo '<p><u><b class="classTitle">Subject: '.$rowClassFind["class_title"].'</b></u></p>';
                            echo '<br>';
                            echo $rowClassFind["description"];
                            echo '<br>';

                            echo '<section id= "'.$sectionId.'" class = "hidePost">';
                            
                            $postPick = "select * from  class_posts where class_id = '$classId'";
                            $resultPost = mysqli_query($con,$postPick);
                            $numClass = mysqli_num_rows($resultPost);

                            while($rowPost = $resultPost->fetch_assoc()){
                                echo '<br><b class="classPostTitle">'.$rowPost['postTitle'].'</b><br>';
                                echo '<br class="classPostDescription">'.$rowPost['description'];
                                echo '<br>'."<a href='view.php?post_id=".$rowPost['post_id']."'>".$rowPost['name']."</a>";
                                echo '<br><br>';
                            }
                            echo '</section>';
                        echo '</section>';
                    echo '</section>';
                    echo '';
                    echo '<br>';
                ?>
            <?php endwhile; ?>
    </body>
</html>
