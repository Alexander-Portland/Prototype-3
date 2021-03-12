<?php

session_start();

//usernme and password extracted from login page submission
$nameCheck = $_SESSION['username'];
$passCheck = $_SESSION['password'];

//initial database connection is set
$con = mysqli_connect('localhost','root','');

mysqli_select_db($con,'demo');

//Initial check to see that user is gaining access to the page with a student username and correct password
$studentPick = "select student_id, forname, surname from studentdetails where student_username = '$nameCheck' && student_password = '$passCheck' ";
$resultStudent = mysqli_query($con,$studentPick);
$numStudent = mysqli_num_rows($resultStudent);

//student's ID, first name and last name are extracted from database check query
$row = $resultStudent->fetch_assoc();
$ID = $row['student_id'];
$Fname = $row['forname'];
$Lname = $row['surname'];

//If database check renders no account with the user credentials will result in the user being taken back to index page
if($numStudent == 0){
    $_SESSION['username'] = "";
    header('location:index.php');
}
?>

<html>
    <!--Page is linked to a single css and javascript page-->
    <head>
    <title>Student Page</title>
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="mystyle.css">
    <style>
        <?php include 'mystyle.css'; ?>
    </style>

    <script src="pageInteraction.js"></script>

    </head>

    <main>
        <!--Navigation bar gives user access to their message page and the logout sequence-->
        <nav class="NavBar">
            <p class = "navBarTitle">Welcome <?php echo $Fname ?> <?php echo $Lname?></p>
            <a href="MessengerStudent.php"><b>View Messages</b></a>
            <a href="logout.php"><b>Log out</b></a>
        </nav>

        <?php
            //Query for extracting the class IDs the student has been assigned to
            $classPick = "select student_id, class_id from studentdetails_classdetails where student_id = '$ID'";
            $resultClass = mysqli_query($con,$classPick);
            $numClass = mysqli_num_rows($resultClass);

            //System will loop through every extracted class the student is assigned to
            while($rowClass = $resultClass->fetch_assoc()): ?>
                <?php
                    //The class section needs to be identified with a unique code
                    $sectionId = rand();
                    $classId = $rowClass["class_id"];

                    //The extracted class ID is then used to search for the class details with the same class ID
                    $classFind = "select class_id, class_title, description from classdetails where class_id = '$classId'";
                    $resultClassFind = mysqli_query($con,$classFind);
                    $numClassFind = mysqli_num_rows($resultClassFind);

                    //The results of the class search are then extracted
                    $rowClassFind = $resultClassFind->fetch_assoc();

                    //Each class extracted will be displayed in their own centered section
                    echo '<section class = "centerPosClass">';
                        echo '<Section id = "classDisplay" class="classPosts">';

                            //each class will have (at the top) a display button, class title, class description and section ID
                            echo '<button onclick="hideContent('.$sectionId.')" class="expandButton button">Display</button>';
                            echo '<p><u><b class="classTitle">'.$rowClassFind["class_title"].'</b></u></p>';
                            echo '<br>';
                            echo '<p class = "classPostDescription">'.$rowClassFind["description"].'</p>';
                            echo '<br><br><br>';

                            //The class content needs to be in an encapsulated section to facilitate the display and hide button
                            echo '<section id= "'.$sectionId.'">';
                            
                            //Query for the class posts whos class ID is the same as the class of the displayed class section
                            $postPick = "select * from  class_posts where class_id = '$classId'";
                            $resultPost = mysqli_query($con,$postPick);
                            $numClass = mysqli_num_rows($resultPost);

                            //System will then extract each row result of the post query and display the post title, description and file content
                            while($rowPost = $resultPost->fetch_assoc()){
                                //Each post is displayed in it's own clearly section clearly displayed as seperate from the other posts of the class
                                echo '<section class = "classOutliner">';
                                    echo '<br><b class="classPostTitle">'.$rowPost['postTitle'].'</b><br>';
                                    echo '<br><p class="classPostDescription">'.$rowPost['description'].'</p>';
                                    echo '<br><p class="classPostDescription">'."<a href='view.php?post_id=".$rowPost['post_id']."'>".$rowPost['name']."</p></a><br>";
                                echo'</section>';
                                echo '<br><br>';
                            }
                            echo '</section>';
                        echo '</section>';
                    echo '</section>';
                    
                ?>
            <?php endwhile; ?>
        </main>
</html>
