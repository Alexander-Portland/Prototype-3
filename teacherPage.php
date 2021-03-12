<?php

//Start of database phase
session_start();

//username and password are extracted from login sesssion for accont check
$nameCheck = $_SESSION['username'];
$passCheck = $_SESSION['password'];

//First connection to database established
$con = mysqli_connect('localhost','root','');

mysqli_select_db($con,'demo');

//Check to see that session username and password are the same as an existing teaching account
$teacherPick = "select teacher_id, teacher_forname, teacher_surname from teacherdetails where teacher_username = '$nameCheck' && teacher_password = '$passCheck'";

$resultTeacher = mysqli_query($con,$teacherPick);
$numStudent = mysqli_num_rows($resultTeacher);

//The teacher account ID, first name and last are extracted from the database query
$row = $resultTeacher->fetch_assoc();
$ID = $row['teacher_id'];
$Fname = $row['teacher_forname'];
$Lname = $row['teacher_surname'];


//If the searched account does not exist then the user is not using an authorised account they are sent back to the index page
if($numStudent == 0){
    $_SESSION['username'] = "";
    header('location:index.php');
}



?>
<html>
    <!--Page is linked to a single css and javascript page -->
    <head>
    <title>Teacher Page</title>
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="mystyle.css">
    <style>
        <?php include 'mystyle.css'; ?>
    </style>
    <script src="pageInteraction.js"></script>
    </head>

    <main>

        <!--Navigation bar gives future developers the chance to append new pages to a function nav system-->
        <nav class="NavBar">
            <p class = "navBarTitle">Welcome <?php echo $Fname ?> <?php echo $Lname?></p>
            <a href="MessengerTeacher.php"><b>View Messages</b></a>
            <a href="logout.php"><b>Log out</b></a>
        </nav>

        <!--Help page-->
        <section id="myhelp" class="help">
            <section class="helpContent">
                <!--The help box will contains a close button, help title and the tutorial hints-->
                <span id = "helpClose" class="close">&times;</span>
                <label class = "loginLabel"><b>Using the teacher's homepage</b></label>
                <p class = "loginHelpText">To upload, delete and update class content, take the following steps</p>
                <!--This is the text part of the tutorial-->
                <ol>
                
                    <li>To add class content either press the "add" button at the top and manually input the you're upload criteria. Or you can select the class you wish to upload to by pressing the "add" button encapsualted in the selected class, from there take the following steps:</li>
                        <ul>
                            <li>Enter the name of the class you wish to asign the content to in the "Class Name" box</li>
                            <li>Enter the title/topic name of the upload to the "post title" box</li>
                            <li>Add you're description/goal of the upload to the "description" box</li>
                            <li>Choose the file you wish to upload by first pressing the "choose file" box</li>
                            <li>When satisfied with you're upload, submit it by pressing the "upload" button</li>
                        </ul>
                    <li>To delete class content either press the "delete" button at the top and manually input the class name and content title you wish to delete. Or you can select the content you wish to delete by the pressing the "delete" button encapsulated in the content you wish to delete then take the following steps:</li>
                        <ul>
                            <li>Check that the content you wish to delete has been correctly selected by checking the "class name" and "post title boxes. Finally submit the deletion by pressing the "delete button"</li>
                        </ul>
                    <li>To add class content either press the "add" button at the top and manually input the you're upload criteria. Or you can select the class you wish to upload to by pressing the "add" button encapsualted in the selected class, from there take the following steps:</li>
                        <ul>
                            <li>Enter the name of the class you wish to asign the update to in the "Class Name" box</li>
                            <li>Enter the updated title/topic name of the upload to the "post title" box</li>
                            <li>Add you're updated description/goal of the upload to the "description" box</li>
                            <li>Choose the updated/unchanged file you wish to upload by first pressing the "choose file" box</li>
                            <li>When satisfied with you're update, submit it by pressing the "update" button</li>
                        </ul>

                </ol>
                <!--This is the video part of the tutorial -->
                <video class = "helpVideo" controls>
                    <source src="" type="video/mp4">
                </video>
            </section>
        </section>

        <!--This section contains the applications allowing the teacher to upload, edit or delete class content -->
        <section class = "centerPosClass">
            <section class = "classPosts">
                <!--The application box contains a title for each application of adding, deleting or updating class work  -->
                <p id = "addClassTitle" class = "teacherInteractionBoxTitle hidepost">Add Class Work</p>
                <p id = "deleteClassTitle" class = "teacherInteractionBoxTitle hidepost">Delete Class Work</p>
                <p id = "updateClassTitle" class = "teacherInteractionBoxTitle hidepost">Update Class Work</p>

                <!--The help button is displayed as an interactable picture -->
                <img src="img\helpButton.png" id="helpBtn" alt="Missing help button" class = "helpButton" width = 40x><br>

                <!--This section contains the application for adding new posts to a selected class -->
                <section id = "classAdd" class = "hidepost">
                    <!--The adding application contains five inputs: post title, description, file upload, confirm button and abort button -->
                    <form action="teacherPageSubmissions.php" method="post" enctype="multipart/form-data">
                        <input type="text" name ="classAddID" class = "hidepost"><br>
                        <b><p class = "displayInline">Class Name: </p></b> <p id = "className" class = "displayInline"></p><br>
                        <p class = "displayInline"><b>Post Title: </b></p> <input type="text" name="postName" class = "inputButton" required><br>
                        <p class = "displayInline"><b>Description: </b></p> <textarea type="text" name="classDescription" class = "textInput" required></textarea><br>
                        <input type="file" name="myfile" required><br>
                        <p><b>Are you sure you wish to add this to the class?</b></p>
                        <!--When pressed the system will call the upload function in teacherPageSubmissions.php -->
                        <button name="btn" class = "button buttonGreen">Yes</button>
                        <!--When pressed the system will abort the upload request and close the adding applications section --> 
                        <button onclick="abort();" class = "button buttonRed" type = "button">No</button>
                    </form>
                </section>
                
                <!--This sections contains the application for deleting posts -->
                <section id = "classDelete" class = "hidePost">
                    <!--The delete application displays the target post's class name, title and two buttons that either confirm the action or cancel it -->
                    <form action="teacherPageSubmissions.php" method="post">
                        <input type="text" name ="classDeleteID" class = "hidepost"><br>
                        <b><p class = "displayInline">Class Name: </p></b><p id = "classNameDelete" class = "displayInline"></p><br> 
                        <b><p class = "displayInline">Post Title: </p></b><p id = "postNameDelete" class = "displayInline"></p><br>
                        <b><p>Are you sure you want to delete this?</p></b>
                        <!--When pressed the system will call the delete function in teacherPageSubmissions.php -->
                        <button name="btnDelete" class = "button buttonGreen">Yes</button>
                        <!--When pressed the system will abort the upload request and close the delete applications section -->
                        <button onclick="abort();" class = "button buttonRed" type = "button">No</button>
                    </form>
                </section>

                <!--This section contains the application for updating existing posts-->
                <section id = "classSearch" class = "hidePost">
                    <!--The update application contains inputs for the post title, description and file upload that can be changed as well as a confirm and abort button -->
                    <form action="teacherPageSubmissions.php" method="post" enctype="multipart/form-data">
                        <input type="text" name ="classUpdateID" class = "hidePost"><br>
                        <label>Class Name: </label><p id = "classNameSearch" class = "displayInline"></p><br>
                        <label>Post Title: </label><input type="text" name="postNameSearch" class = "inputButton" required><br>
                        <label>Description: </label><textarea type="text" name="classDescriptionSearch" class = "textInput" required></textarea><br>
                        <input type="file" name="myfileUpdate" required><br>
                        <b><p>Are you sure you want to update this?</p></b>
                        <!--When pressed the system will call the update function in teacherPageSubmissions.php -->
                        <button name="search" class = "button buttonGreen">Yes</button>
                        <!--When pressed the system will abort the update request and close the delete applications section -->
                        <button onclick="abort();" class = "button buttonRed" type = "button">No</button>
                    </form>
                </section>

                
            </section>
        </section>

        <?php
            //This php section is responsible for calling all assigned classes and content and displaying them

            //When started the system will call for classes that have been assigned to the user
            $classPick = "select teacher_id, class_id from teacherdetails_classdetails where teacher_id = '$ID'";
            $resultClass = mysqli_query($con,$classPick);
            $numClass = mysqli_num_rows($resultClass);

            //The system then loops through each result of the class query
            while($rowClass = $resultClass->fetch_assoc()): ?> 
                <?php
                    //randomly generated ID codes are needed to identify the section, title and class location
                    $sectionId = rand();
                    $classTitle = rand();
                    $classIDLocation = rand();

                    //The ID for the selected class is then extracted and used to search for the classes title and description for display
                    $classId = $rowClass["class_id"];
                    $classFind = "select class_id, class_title, description from classdetails where class_id = '$classId'";

                    $resultClassFind = mysqli_query($con,$classFind);
                    $numClassFind = mysqli_num_rows($resultClassFind);

                    //The results of the class query are then extracted for final display
                    $rowClassFind = $resultClassFind->fetch_assoc();
                    echo '<section class = "centerPosClass">';
                        echo '<section id = "classDisplay" class="classPosts">';
                            //Each class is given a display button which will minimize or reveal the class posts of its assigned class
                            echo '<button onclick="hideContent('.$sectionId.')" class="expandButton button">Display</button>';

                            echo '<p name ="classAddID" id= "'.$classIDLocation.'" class="hidepost">'.$classId.'</p>';

                            //The class title and description are then displayed
                            echo '<p id = "'.$classTitle.'"><u><b class="classTitle">'.$rowClassFind["class_title"].'</b></u></p>';
                            echo '<br>';
                            echo '<p class = "classPostDescription">'.$rowClassFind["description"].'</p>';
                            echo '<br><br><br>';
                            
                            //The section below contains all of the uploaded class content to the displayed class
                            echo '<section id= "'.$sectionId.'" class = "">';
                                //The class posts for the displayed class are then extracted from the database
                                $postPick = "select * from  class_posts where class_id = '$classId'";
                                $resultPost = mysqli_query($con,$postPick);
                                $numClass = mysqli_num_rows($resultPost);

                                //The results of the posts query are extracted and looped through for display
                                while($rowPost = $resultPost->fetch_assoc()){
                                    //Randomly generated IDs are needed to identify the post ID, title and description
                                    $postId = rand();
                                    $postTitle = rand();
                                    $postDescription = rand();
                                    //The following section is used to display the post title description and file content
                                    echo '<section class = "classOutliner">';
                                            echo '<p name ="postID" id= "'.$postId.'" class="hidepost">'.$rowPost['post_id'].'</p>';
                                            echo '<br><b id="'.$postTitle.'"class="classPostTitle">'.$rowPost['postTitle'].'</b><br>';
                                            echo '<br><p class="classPostDescription" id = "'.$postDescription.'">'.$rowPost['description'].'<p>';
                                            echo '<br><p class="classPostDescription">'."<a href='view.php?post_id=".$rowPost['post_id']."'>".$rowPost['name']."</p></a><br>";
                                            echo '<button onclick ="deleteSend('.$postId.','.$classTitle.','.$postTitle.')" class="button">Delete</button>';
                                            echo'<button  onclick ="updateSend('.$postId.','.$classTitle.','.$postTitle.','.$postDescription.')" class="button">Update</button><br>';
                                    echo '</section>';
                                    echo '<br><br>';
                                }
                            echo '</section>';
                            //Each add application section has an add button which will open the add posts application
                            echo'<button onclick="addSend('.$classIDLocation.','.$classTitle.')" class="button">Add</button>';
                        echo '</section>';
                    echo '</section>';
                ?>
        <?php endwhile; ?>

    </section>

    <!--The script below is used to display and hide the help/tutorial box -->
    <script>
            var selectHelp = document.getElementById("myhelp");
            var sentMessage = document.getElementById("messageSuccess");

            var btn = document.getElementById("helpBtn");
            var span = document.getElementById("helpClose");

            btn.onclick = function() {
                selectHelp.style.display = "block";
            }

            span.onclick = function() {
                selectHelp.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == selectHelp) {
                    selectHelp.style.display = "none";
                }
            }
    </script>

    </main>
</html>