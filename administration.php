<?php

session_start();

$nameCheck = $_SESSION['username'];
$passCheck = $_SESSION['password'];

$con = mysqli_connect('localhost','root','');

mysqli_select_db($con,'demo');

$teacherPick = "select admin_ID, forename, surname from admin where admin_username = '$nameCheck' && admin_password = '$passCheck' ";


$resultStudent = mysqli_query($con,$teacherPick);
$numStudent = mysqli_num_rows($resultStudent);

$row = $resultStudent->fetch_assoc();
$ID = $row['admin_ID'];
$Fname = $row['forename'];
$Lname = $row['surname'];

if($numStudent == 0){
    $_SESSION['username'] = "";
    header('location:index.php');
}
?>

<html>
    <head>
        <title>Admin</title>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="mystyle.css">
        <style>
            <?php include 'mystyle.css'; ?>
        </style>
        <script src="pageInteraction.js"></script>
        </head>
    </head>

    <nav class="NavBar">
        <p class = "navBarTitle">Welcome <?php echo $Fname ?> <?php echo $Lname?></p>
        <a href="logout.php"><b>Log out</b></a>
    </nav>

    <section id="myhelp" class="help">
        <section class="helpContent">
            <span id = "helpClose" class="close">&times;</span>
            <label class = "loginLabel"><b>Using the questions and answers page</b></label>
            <p class = "loginHelpText">To view you're questions and answers, take the following steps:</p>
            <ol>
            
                <li>Press "inbox" button to view you're questions that have been answered</li>
                <li>Press "sent" button to view you're questions that have not been answered"</li>
                <li>Press "add" button to begin writing a new question then take the following steps: </li>
                <ul>
                    <li>Enter the username of the teacher you wish to submit the question to in the "Recipient" box</li>
                    <li>Enter the title of the topic/subject your question is related to in the "Question Title" box</li>
                    <li>Finally, enter the you'r question in the "Question" box</li>
                <ul>

            </ol>
            <video class = "helpVideo" controls>
                <source src="" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </section>
    </section>

    <section class = "centerPosClass">
            <section class = "classPosts">
                <img src="img\helpButton.png" id="helpBtn" alt="Missing help button" class = "helpButton" width = 40x>
                <button onclick="viewClasses()" class= "button">View Classes</button>
                <button onclick="addClass()" class= "button">Add Classes</button>
            </section>
    </section>

    <main>

    <section id = "classAdd" class = "centerPosClass hidePost">
            <section class = "classPosts">
                    <form action="addNewClass.php" method="post">
                        <p class = "teacherInteractionBoxTitle">Add New Class</p> 
                        <label><b>Class Title: </b></label><input type="text" name="classNameInput" class = "inputButton" required><br>
                        <label><b>Class Description:  </b></label><textarea type="text" name="classDescriptionInput" class = "textInput" required></textarea><br>
                        <p><b>Are you sure you want to send this question?</b></p>
                        <button name="sendNewClass" class = "button buttonGreen">Yes</button>
                        <button onclick="closeAddClass()" class = "button buttonRed">No</button>  
                    </form> 
            </section>
        </section>

        <section id = "deleteClass" class = "centerPosClass hidePost">
                <section class = "classPosts">
                    <p class = "teacherInteractionBoxTitle">Delete Class</p> 
                    <form action="deleteClasses.php" method="post">
                        <input type="text" name ="classDeleteID" class = "hidepost"><br>
                        <label class = "displayInline"><b>Class Name: </b></label><p id = "deleteClassName" class = "displayInline"></p><br> 
                        <label class = "displayInline"><b>Class Description: </b></label><p id = "deleteClassDescription" class = "displayInline"></p><br>
                        <b><p>Are you sure you want to delete this?</p></b>
                        <button name="btnDelete" class = "button buttonGreen">Yes</button>
                        <button onclick="closeDeleteClass()" class = "button buttonRed">No</button>
                    </form>
            </section>
        </section>

        <section id = "updateClass" class = "centerPosClass hidePost">
            <section class = "classPosts">
                <p class = "teacherInteractionBoxTitle">Update Class</p>
                    <form action="updateClass.php" method="post">
                        <input type="text" name ="classUpdateID" class = "hidePost"><br>
                        <label>Class Name: </label><input type="text" name="classUpdate" class = "inputButton" required><br>
                        <label>Class Description: </label><textarea type="text" name="classDescriptionUpdate" class = "textInput" required></textarea><br>
                        <b><p>Are you sure you want to update this?</p></b>
                        <button name="search" class = "button buttonGreen">Yes</button>
                        <button onclick="closeUpdateClass()" class = "button buttonRed">No</button>
                    </form>
            </section>
        </section>

        <section id = "classViewer" class = "centerPosClass hidePost">
            <section class = "classPosts">
                <p class = "teacherInteractionBoxTitle">View Classes</p>
                <?php
                    $classPick = "select * from classdetails messages";
                    $resultClass = mysqli_query($con,$classPick);
                    $numClass = mysqli_num_rows($resultClass);
                    if($numClass >= 1){
                        while($rowClass = $resultClass->fetch_assoc()): ?> 
                            <?php
                                $classElementId = rand();
                                $classElementTitle = rand();
                                $classElementDescription = rand();
                                $classID = $rowClass["class_id"];
                                $classTitleID = $rowClass["class_title"];
                                $classElementDescriptionID = $rowClass["description"];
                                echo '<br><section class = "classOutliner">';
                                    echo '<b><p class = "displayInline">Class ID: </p></b> <p id = '.$classElementId.' class = "displayInline">'.$classID.'</p><br>';
                                    echo '<b><p class = "displayInline">Class Title: </p></b><p id = '.$classElementTitle.' class = "displayInline">'.$classTitleID.'</p><br>';
                                    echo '<b><p class = "displayInline">Class Description: </p></b><p id = '.$classElementDescription.' class = "displayInline">'.$classElementDescriptionID.'</p><br>';
                                    echo '<button onclick ="classDeleteSend('.$classElementId.','.$classElementTitle.','.$classElementDescription.')" class="button">Delete</button>';
                                    echo '<button onclick ="updateClass('.$classElementId.','.$classElementTitle.','.$classElementDescription.')" class="button">Update</button>';
                                echo '</section>';
                            ?>
                                 
                            <?php endwhile;
                    }
                    else{
                        echo '<p>No classes have been added to the system.</p>';
                    }
                ?>
            </section>
        </section>

        <script>
            var selectHelp = document.getElementById("myhelp");
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