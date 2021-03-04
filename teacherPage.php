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
                    Your browser does not support the video tag.
                </video>
            </section>
        </section>

        
        <section class = "centerPosClass">
            <section class = "classPosts">
                <button onclick="hideContent('classAdd') ,minimise('classDelete') ,minimise('classSearch') ,minimise('helpDelete'), minimise('helpUpdate')" class = "button">Add Class Work</button>
                <button onclick="hideContent('classDelete') ,minimise('classAdd') ,minimise('classSearch') ,minimise('helpAdd'), minimise('helpUpdate') " class = "button">Delete post</button>
                <button onclick="hideContent('classSearch') ,minimise('classAdd') ,minimise('classDelete') ,minimise('helpAdd'), minimise('helpDelete')" class = "button">Update post</button>
                <img src="img\helpButton.png" id="helpBtn" alt="Missing help button" class = "helpButton expandButton" width = 40x><br>
                <section id = "classAdd" class = "hidePost">
                    <b>Add posts</b>
                    <form method="post" enctype="multipart/form-data">
                        <label>Class Name: </label><input type="text" name="className" required><br>
                        <label>Post Title: </label><input type="text" name="postName" require><br>
                        <label>Description: </label><input type="text" name="classDescription" required><br>
                        <input type="file" name="myfile" required><br>
                        <button name="btn">Upload</button>
                        <button type = "button" class="loginHelpButton" id= "btnAddHelp" onclick="hideContent('helpAdd')">?</button> 
                    </form>

                    <section id = "helpAdd" class = "hidePost">
                        <p>To add a new post to a class you need to input the class name into the input "class name",
                        <br>input the post name into the input "post title", input the post description into the input "description"
                        <br>and finally press the choose file and select the file you wish to upload and then press the "upload" button
                        </p>
                    </section>

                </section>
                
                <section id = "classDelete" class = "hidePost">
                    <b>Delete posts</b>
                    <form method="post" enctype="multipart/form-data">
                        <label>Class Name: </label><input type="text" name="classNameDelete" required><br>
                        <label>Post Title: </label><input type="text" name="postNameDelete" require><br>
                        <button name="btnDelete">Delete</button>
                        <button type = "button" class="loginHelpButton" id= "btnDeleteHelp" onclick="hideContent('helpDelete')">?</button> 
                    </form>
                    <section id = "helpDelete" class = "hidePost">
                        <p>To delete a post you need to input the class name into the input "class name",
                        <br>input the post name into the input "post title" and finally press the "delete" button
                        </p>
                    </section>
                </section>

                <section id = "classSearch" class = "hidePost">
                    <b>Update posts</b>
                    <form method="post" enctype="multipart/form-data">
                        <label>Class Name: </label><input type="text" name="classNameSearch" required><br>
                        <label>Post Title: </label><input type="text" name="postNameSearch" require><br>
                        <label>Description: </label><input type="text" name="classDescriptionSearch" required><br>
                        <input type="file" name="myfileUpdate" required><br>
                        <button name="search">Update</button>
                        <button type = "button" class="loginHelpButton" id= "btnUpdateHelp" onclick="hideContent('helpUpdate')">?</button> 
                    </form>
                    <section id = "helpUpdate" class = "hidePost">
                        <p>To update a post you need to input the class name into the input "class name",
                        <br>input the post name into the input "post title", input the post description into the input "description"
                        <br>and finally press the choose file and select the file you wish to update and then press the "update" button
                        </p>
                    </section>
                </section>

                <section id = "update" class = "hidePost">
                    <b>Update posts</b>
                    <form method="post" enctype="multipart/form-data">
                        <label>Class Name: </label><input type="text" name="classNameSearchResult" required><br>
                        <label>Post Title: </label><input type="text" name="postNameSearchResult" require><br>
                        <label>Description: </label><input type="text" name="classDescriptionSearchResult" required><br>
                        <input type="file" name="myfileUpdate" required><br>
                        <button name="update" class = "button">Update</button>
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
                    $classId = $rowClass["class_id"];
                    $classFind = "select class_id, class_title, description from classdetails where class_id = '$classId'";

                    $resultClassFind = mysqli_query($con,$classFind);
                    $numClassFind = mysqli_num_rows($resultClassFind);

                    $rowClassFind = $resultClassFind->fetch_assoc();
                    echo '<section class = "centerPosClass">';
                        echo '<section id = "classDisplay" class="classPosts">';
                            echo '<button onclick="hideContent('.$sectionId.')" class="expandButton button">Display</button>';
                            
                            echo '<p><u><b class="classTitle">Subject: '.$rowClassFind["class_title"].'</b></u></p>';
                            echo '<br>';
                            echo $rowClassFind["description"];
                            echo '<br>';
                            
                            echo '<section id= "'.$sectionId.'" class = "">';

                            $postPick = "select * from  class_posts where class_id = '$classId'";
                            $resultPost = mysqli_query($con,$postPick);
                            $numClass = mysqli_num_rows($resultPost);

                            while($rowPost = $resultPost->fetch_assoc()){
                                $editButtonID = rand();
                                echo '<section>';
                                    echo '<section>';
                                        echo '<br><b class="classPostTitle">'.$rowPost['postTitle'].'</b><br>';
                                        echo '<br class="classPostDescription">'.$rowPost['description'];
                                        echo '<br>'."<a href='view.php?post_id=".$rowPost['post_id']."'>".$rowPost['name']."</a>";
                                    echo '</section>';
                                echo '</section>';
                                echo '<br><br>';
                            }
                            echo '</section>';
                        echo '</section>';
                    echo '</section>';
                    echo '<br>';
                ?>
        <?php endwhile; ?>

        <?php 
                
            $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");
            if(isset($_POST['btn'])){
                    
                $className = $_POST['className'];
                $postName = $_POST['postName'];
                $classDescription = $_POST['classDescription'];
                $classPick = "select class_id from classdetails where class_title = '$className'";
                $resultClass = mysqli_query($con,$classPick);
                $numAddResult = mysqli_num_rows($resultClass);


                if($numAddResult == 1){
                    $resultClassId = $resultClass->fetch_assoc();
                    $classIdSelect = $resultClassId['class_id'];
                    $postPick = "select class_id from class_posts where class_id = '$classIdSelect' && postTitle = '$postName'";
                    $resultPost = mysqli_query($con,$postPick);                    
                    $numPostResult = mysqli_num_rows($resultPost);

                    if($numPostResult == 1){
                        echo "<script type='text/javascript'>alert('There is already a post with the same title');</script>";
                    }
                    else{

                            $classCheck = "select id from teacherdetails_classdetails where teacher_id = '$ID' && class_id = '$classIdSelect'";
                            $classCheckQuery = mysqli_query($con,$classCheck); 
                            $classCheckQuery =  mysqli_num_rows($classCheckQuery);

                            if($classCheckQuery == 1){
                                $name = $_FILES['myfile']['name'];
                                $type = $_FILES['myfile']['type'];

                                $data = file_get_contents($_FILES['myfile']['tmp_name']);

                                $stmt = $dbh->prepare("insert into class_posts values('',?,?,?,?,?,?)");
                                $stmt->bindParam(1,$classIdSelect);
                                $stmt->bindParam(2,$postName);
                                $stmt->bindParam(3,$classDescription);
                                $stmt->bindParam(4,$name);
                                $stmt->bindParam(5,$type);
                                $stmt->bindParam(6,$data);
                                $stmt->execute();
                            }

                            else{
                                echo "<script type='text/javascript'>alert('You cannot upload a post to a class you are not assigned to');</script>";
                            }
                        }
                    }
                    else{
                        echo "<script type='text/javascript'>alert('The class you selected does not exist');</script>";
                    }
                }
            ?>

            

        <?php
            if(isset($_POST['btnDelete'])){

                $className = $_POST['classNameDelete'];
                $postTitle = $_POST['postNameDelete'];
                $classFind = "select class_id from classdetails where class_title = '$className'";
                $resultClassFind = mysqli_query($con,$classFind);
                $numDeleteResult = mysqli_num_rows($resultClassFind);

                $rowClassFind = $resultClassFind->fetch_assoc();
                $foundClassId = $rowClassFind['class_id'];

                if($numDeleteResult == 1){

                    $classCheck = "select id from teacherdetails_classdetails where teacher_id = '$ID' && class_id = '$foundClassId'";
                    $classCheckQuery = mysqli_query($con,$classCheck); 
                    $classCheckQueryCount =  mysqli_num_rows($classCheckQuery);   
                    if($classCheckQueryCount == 1){
                        $postFind = "select class_id from class_posts where postTitle = '$postTitle'";
                        $resultClassNameFind = mysqli_query($con,$postFind);
                        $numDeletePostResult = mysqli_num_rows($resultClassNameFind);

                        if($numDeletePostResult >= 1){
                        
                            $postName = $_POST['postNameDelete'];
                            $postDelete = "delete from class_posts where class_id = '$foundClassId' && postTitle = '$postName'";
                            $postDeleteQuery = mysqli_query($con,$postDelete);
                        }
                        else{
                            echo "<script type='text/javascript'>alert('The post you selected does not exist');</script>";
                        }
                    }
                    else{
                        echo "<script type='text/javascript'>alert('You cannot delete a post to a class you are not assigned to');</script>";
                    }
                } 

                else{
                    echo "<script type='text/javascript'>alert('The class you selected does not exist');</script>";
                }
                }
            ?>

        <?php
            $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");
            if(isset($_POST['search'])){
                $classNameSearch = $_POST['classNameSearch'];
                $classTitleSearch = $_POST['postNameSearch'];
                $classDescriptionSearch = $_POST['classDescriptionSearch'];
                $classFindSearch = "select class_id from classdetails where class_title = '$classNameSearch'";

                $resultClassFind = mysqli_query($con,$classFindSearch);
                $numClassResult = mysqli_num_rows($resultClassFind);

                $rowClassFind = $resultClassFind->fetch_assoc();

                if($numClassResult == 1){
                    $foundClassId = $rowClassFind['class_id'];
                    
                    $classCheck = "select id from teacherdetails_classdetails where teacher_id = '$ID' && class_id = '$foundClassId'";
                    $classCheckQuery = mysqli_query($con,$classCheck); 
                    $classCheckQueryCount =  mysqli_num_rows($classCheckQuery);

                    if($classCheckQueryCount == 1){
                        $classPostSearch = "select post_id from class_posts where postTitle = '$classTitleSearch' && class_id = '$foundClassId'";
                        $resultPostFind = mysqli_query($con,$classPostSearch);
                        $numPostResult = mysqli_num_rows($resultPostFind);

                        if($numPostResult == 1){
                            $rowPostFind = $resultPostFind->fetch_assoc();

                            $postID = $rowPostFind['post_id'];
                            $name = $_FILES['myfileUpdate']['name'];
                            $type = $_FILES['myfileUpdate']['type'];

                            $data = file_get_contents($_FILES['myfileUpdate']['tmp_name']);
                          
                            $update = $dbh->prepare("update class_posts set description = ?, name = ?, mine = ?, data = ? where post_id = ?");
                            $update->bindParam(1,$classDescriptionSearch);
                            $update->bindParam(2,$name);
                            $update->bindParam(3,$type);
                            $update->bindParam(4,$data);
                            $update->bindParam(5,$postID);
                            $update->execute();
                            
                            
                        }

                        

                        else{
                            echo "<script type='text/javascript'>alert('The post you are searching for does not exist');</script>";
                        }
                    }
                    else{
                        echo "<script type='text/javascript'>alert('You cannot search a post to a class you are not assigned to');</script>";
                    }
                }
                else{
                    echo "<script type='text/javascript'>alert('The class you selected does not exist');</script>";
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