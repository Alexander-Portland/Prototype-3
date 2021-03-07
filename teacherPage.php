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


if($numStudent == 0){
    $_SESSION['username'] = "";
    header('location:index.php');
}



?>

<html>
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

        <nav class="NavBar">
            <p class = "navBarTitle">Welcome <?php echo $Fname ?> <?php echo $Lname?></p>
            <a href="MessengerTeacher.php"><b>View Messages</b></a>
            <a href="logout.php"><b>Log out</b></a>
        </nav>

        <section id="myhelp" class="help">
            <section class="helpContent">
                <span id = "helpClose" class="close">&times;</span>
                <label class = "loginLabel"><b>Using the teacher's homepage</b></label>
                <p class = "loginHelpText">To upload, delete and update class content, take the following steps</p>
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
                <video class = "helpVideo" controls>
                    <source src="" type="video/mp4">
                </video>
            </section>
        </section>

        
        <section class = "centerPosClass">
            <section class = "classPosts">
                <p id = "addClassTitle" class = "teacherInteractionBoxTitle hidepost">Add Class Work</p>
                <p id = "deleteClassTitle" class = "teacherInteractionBoxTitle hidepost">Delete Class Work</p>
                <p id = "updateClassTitle" class = "teacherInteractionBoxTitle hidepost">Update Class Work</p>
                <img src="img\helpButton.png" id="helpBtn" alt="Missing help button" class = "helpButton" width = 40x><br>
                <section id = "classAdd" class = "hidepost">
                    <form method="post" enctype="multipart/form-data">
                        <input type="text" name ="classAddID" class = "hidepost"><br>
                        <b><p class = "displayInline">Class Name: </p></b> <p id = "className" class = "displayInline"></p><br>
                        <p class = "displayInline"><b>Post Title: </b></p> <input type="text" name="postName" class = "inputButton" required><br>
                        <p class = "displayInline"><b>Description: </b></p> <textarea type="text" name="classDescription" class = "textInput" required></textarea><br>
                        <input type="file" name="myfile" required><br>
                        <p><b>Are you sure you wish to add this to the class?</b></p>
                        <button name="btn" class = "button buttonGreen">Yes</button> 
                        <button onclick="abort();" class = "button buttonRed">No</button>
                    </form>
                    
                </section>
                
                <section id = "classDelete" class = "hidePost">
                    <form method="post" enctype="multipart/form-data">
                        <input type="text" name ="classDeleteID" class = "hidepost"><br>
                        <b><p class = "displayInline">Class Name: </p></b><p id = "classNameDelete" class = "displayInline"></p><br> 
                        <b><p class = "displayInline">Post Title: </p></b><p id = "postNameDelete" class = "displayInline"></p><br>
                        <b><p>Are you sure you want to delete this?</p></b>
                        <button name="btnDelete" class = "button buttonGreen">Yes</button>
                        <button onclick="abort();" class = "button buttonRed">No</button>
                    </form>
                </section>

                <section id = "classSearch" class = "hidePost">
                    <form method="post" enctype="multipart/form-data">
                        <input type="text" name ="classUpdateID" class = "hidePost"><br>
                        <label>Class Name: </label><p id = "classNameSearch" class = "displayInline"></p><br>
                        <label>Post Title: </label><input type="text" name="postNameSearch" class = "inputButton" required><br>
                        <label>Description: </label><textarea type="text" name="classDescriptionSearch" class = "textInput" required></textarea><br>
                        <input type="file" name="myfileUpdate" required><br>
                        <b><p>Are you sure you want to update this?</p></b>
                        <button name="search" class = "button buttonGreen">Yes</button>
                        <button onclick="abort();" class = "button buttonRed">No</button>
                    </form>
                </section>

                
            </section>
        </section>

        <?php
            $classPick = "select teacher_id, class_id from teacherdetails_classdetails where teacher_id = '$ID'";
            $resultClass = mysqli_query($con,$classPick);
            $numClass = mysqli_num_rows($resultClass);


            while($rowClass = $resultClass->fetch_assoc()): ?> 
                <?php 
                    $sectionId = rand();
                    $classTitle = rand();
                    $classIDLocation = rand();
                    $classId = $rowClass["class_id"];
                    $classFind = "select class_id, class_title, description from classdetails where class_id = '$classId'";

                    $resultClassFind = mysqli_query($con,$classFind);
                    $numClassFind = mysqli_num_rows($resultClassFind);

                    $rowClassFind = $resultClassFind->fetch_assoc();
                    echo '<section class = "centerPosClass">';
                        echo '<section id = "classDisplay" class="classPosts">';
                            echo '<button onclick="hideContent('.$sectionId.')" class="expandButton button">Display</button>';
                            echo '<p name ="classAddID" id= "'.$classIDLocation.'" class="hidepost">'.$classId.'</p>';
                            echo '<p id = "'.$classTitle.'"><u><b class="classTitle">'.$rowClassFind["class_title"].'</b></u></p>';
                            echo '<br>';
                            echo $rowClassFind["description"];
                            echo '<br><br><br>';
                            
                            echo '<section id= "'.$sectionId.'" class = "">';

                            $postPick = "select * from  class_posts where class_id = '$classId'";
                            $resultPost = mysqli_query($con,$postPick);
                            $numClass = mysqli_num_rows($resultPost);

                            while($rowPost = $resultPost->fetch_assoc()){
                                $postId = rand();
                                $postTitle = rand();
                                $postDescription = rand();
                                echo '<section class = "classOutliner">';
                                        echo '<p name ="postID" id= "'.$postId.'" class="hidepost">'.$rowPost['post_id'].'</p>';
                                        echo '<br><b id="'.$postTitle.'"class="classPostTitle">'.$rowPost['postTitle'].'</b><br>';
                                        echo '<br><p class="classPostDescription" id = "'.$postDescription.'">'.$rowPost['description'].'<p>';
                                        echo '<br>'."<a href='view.php?post_id=".$rowPost['post_id']."'>".$rowPost['name']."</a><br>";
                                        echo '<button onclick ="deleteSend('.$postId.','.$classTitle.','.$postTitle.')" class="button">Delete</button>';
                                        echo'<button  onclick ="updateSend('.$postId.','.$classTitle.','.$postTitle.','.$postDescription.')" class="button">Update</button><br>';
                                echo '</section>';
                                echo '<br><br>';
                            }
                            echo '</section>';
                            echo'<button onclick="addSend('.$classIDLocation.','.$classTitle.')" class="button">Add</button>';
                        echo '</section>';
                    echo '</section>';
                    echo '<br>';
                ?>
        <?php endwhile; ?>

        <?php 
                
            $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");
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
                }
                else{
                    echo "<script type='text/javascript'>alert('upload failed to process');</script>";
                }
                }
            ?>

            

        <?php
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
                    echo "<script type='text/javascript'>alert('Deletion failed to process');</script>";
                }
                }
            ?>

        <?php
            $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");
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
                }
            }
        ?>

    </section>
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