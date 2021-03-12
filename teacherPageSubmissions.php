<?php

    session_start();

    //The username and password of the session are extracted for checks
    $nameCheck = $_SESSION['username'];
    $passCheck = $_SESSION['password'];

    //First connection to database
    $con = mysqli_connect('localhost','root','');

    mysqli_select_db($con,'demo');

    //First query to check that the session has the username and password of an existing teacher account
    $teacherPick = "select teacher_id, teacher_forname, teacher_surname from teacherdetails where teacher_username = '$nameCheck' && teacher_password = '$passCheck' ";

    $resultTeacher = mysqli_query($con,$teacherPick);
    $numStudent = mysqli_num_rows($resultTeacher);

    //This new connection is primarily used for the adding and update functions as they handle files
    $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");

    //If the user is attempting to gain acces to the page without logining as a teacher, they will be redirected to the index page
    if($numStudent == 0){
        $_SESSION['username'] = "";
        header('location:index.php');
    }

    //User ID, first name and last name are extracted
    $row = $resultTeacher->fetch_assoc();
    $ID = $row['teacher_id'];
    $Fname = $row['teacher_forname'];
    $Lname = $row['teacher_surname'];

    //When the yes button on the add application on teacherPage.php is pressed this function is executed
    if(isset($_POST['btn'])){
        //The class ID, post name and post description are extracted and filtered
        $classId = $_POST['classAddID'];
        $postName = htmlspecialchars($_POST['postName'],ENT_COMPAT);
        $classDescription = htmlspecialchars($_POST['classDescription'],ENT_COMPAT);

        //The extracted class ID is then searched for to check its a valid class to upload to
        $classPick = "select class_id from classdetails where class_id = '$classId'";
        $resultClass = mysqli_query($con,$classPick);
        $numAddResult = mysqli_num_rows($resultClass);

        //If the class ID is valid then the class is confirmed to exist
        if($numAddResult == 1){
            //The system then checks if the user is assigned to the class they are trying to upload to
            $classValid = "select class_id from teacherdetails_classdetails where class_id = '$classId' && teacher_id = '$ID'";
            $classValidExecute = mysqli_query($con,$classValid);
            $classValidNum = mysqli_num_rows($classValidExecute);
            
            //If the user is assigned to the class they are uploading to then the upload process is executed
            if($classValidNum == 1){
            $name = $_FILES['myfile']['name'];
            $type = $_FILES['myfile']['type'];

            $data = file_get_contents($_FILES['myfile']['tmp_name']);

            //Data from the page needs to be inserted into an array for class posts from class ID to the file data
            $stmt = $dbh->prepare("insert into class_posts values('',?,?,?,?,?,?)");
            $stmt->bindParam(1,$classId);
            $stmt->bindParam(2,$postName);
            $stmt->bindParam(3,$classDescription);
            $stmt->bindParam(4,$name);
            $stmt->bindParam(5,$type);
            $stmt->bindParam(6,$data);
            $stmt->execute();
            //When uploading is complete the system returns the user to the teacher homepage 
            header('location:teacherPage.php');
            }
            //If the user is attempting to upload to a class they are not assigned to then the upload is aborted with an error message displayed
            else{
                $errorTitle = "Upload rejected";
                $errorStatement = "You are attempting to upload a post to a different class from the one you selected";
            }
        }
        //If the class ID was invalid then the system will inform the user that the class does not exist
        else{
            $errorTitle = "Upload rejected";
            $errorStatement = "You are attempting to upload a post to a class that does not exist";
        }
        }


    //The function below is only execute when the yes button of the delete application on teacherPage.php is pressed
    if(isset($_POST['btnDelete'])){
        //The ID of the selected post is extracted and used to check that the class exists on the system
        $postID = $_POST['classDeleteID'];
        $classFind = "select post_id, class_id from class_posts where post_id = '$postID'";
        $resultClassFind = mysqli_query($con,$classFind);
        $numDeleteResult = mysqli_num_rows($resultClassFind);

        //If the check shows that the class exist then the system proceeds to the next validation check
        if($numDeleteResult == 1){
            //The class ID of the selected post must be extracted for final validation check
            $rowPostFind = $resultClassFind->fetch_assoc();
            $classId = $rowPostFind['class_id'];

            //The system then checks if the user is assigned to the class they are trying to delete a post from
            $classValid = "select class_id from teacherdetails_classdetails where class_id = '$classId' && teacher_id = '$ID'";
            $classValidExecute = mysqli_query($con,$classValid);
            $classValidNum = mysqli_num_rows($classValidExecute);
            
            //If the check shows the user is assigned to the class they are deleting from then the deletion will begin
            if($classValidNum == 1){
                $postDelete = "delete from class_posts where post_id = '$postID'";
                $postDeleteQuery = mysqli_query($con,$postDelete);
                header('location:teacherPage.php');
            }
            //If the check shows the user is not assigned to the class then the deletion is aborted with a error message displayed
            else{
                $errorTitle = "Delete rejected";
                $errorStatement = "You are attempting to delete a post from a class you are not assigned to";
            }
        }
        //If the check shows that the class does not exist then the deletion is aborted and an error message is displayed
        else{
            $errorTitle = "Delete rejected";
            $errorStatement = "You are attempting to delete a post that does not exist";
        }
        
        }
        //The function below is executed when the yes button of the update function on teacherPage.php is pressed
        if(isset($_POST['search'])){
            //The ID of the post being updated is extracted as well as the post name and description
            $postID = $_POST['classUpdateID'];
            $classTitleSearch = htmlspecialchars($_POST['postNameSearch'],ENT_COMPAT);
            $classDescriptionSearch = htmlspecialchars($_POST['classDescriptionSearch'],ENT_COMPAT);

            //The system then checks if the selected post exists on the system
            $classFindSearch = "select post_id, class_id from class_posts where post_id = '$postID'";
            $resultClassFind = mysqli_query($con,$classFindSearch);
            $numClassResult = mysqli_num_rows($resultClassFind);
            
            //If the check shows the post does exist on the system then the second validation check starts
            if($numClassResult == 1){

                $rowClassFind = $resultClassFind->fetch_assoc();
                $classId = $rowClassFind['class_id'];

                //The system then checks if the user is assigned to the class they are trying to delete a post from
                $classValid = "select class_id from teacherdetails_classdetails where class_id = '$classId' && teacher_id = '$ID'";
                $classValidExecute = mysqli_query($con,$classValid);
                $classValidNum = mysqli_num_rows($classValidExecute);

                //If the check shows that the user has been assigned to the class they are trying to update then the update will be executed
                if($classValidNum == 1){

                    //The file name and type are extracted; followed by the data of that file
                    $name = $_FILES['myfileUpdate']['name'];
                    $type = $_FILES['myfileUpdate']['type'];

                    $data = file_get_contents($_FILES['myfileUpdate']['tmp_name']);
                
                    //The update requires the binding of 6 parameters which make up the post content and identification. When update is the complete the user is taken back to their homepage
                    $update = $dbh->prepare("update class_posts set postTitle = ?, description = ?, name = ?, mine = ?, data = ? where post_id = ?");
                    $update->bindParam(1,$classTitleSearch);
                    $update->bindParam(2,$classDescriptionSearch);
                    $update->bindParam(3,$name);
                    $update->bindParam(4,$type);
                    $update->bindParam(5,$data);
                    $update->bindParam(6,$postID);
                    $update->execute();  
                    header('location:teacherPage.php'); 
                }
                //If the check shows the user has not been assigned to the class they are updating then the update is aborted and an error message is displayed
                else{
                    $errorTitle = "Update rejected";
                    $errorStatement = "You are attempting to update a post of a class you have not been assigned to";
                }

            }
            //If the check shows the post does not exist then the update is aborted and an error message is displayed
            else{
                $errorTitle = "Update rejected";
                $errorStatement = "You are attempting to update a post that does not exist";
        
            }
        }
        
       
?>
<!--The code below is displayed if any of the previous functions are aborted and are used to display error messages -->
<html>
    <head>
        <!--The error message page uses a single css and javascript page for the interface-->
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
        <!--The error message is displayed within a section that is centered-->
        <section class = "centerPosClass">
            <section class = "helpContent">

            <!--The error message has a return button which when pressed will return the user to their homepage-->
            <form action="teacherPage.php">
                <button class= "expandButton button">Return</button>
            </form>

            <!--The error message has been designed to change its text content automatically to the error-->
            <label class = "loginLabel"><?php echo $errorTitle ?></label>
            <p><?php echo $errorStatement ?></p>
            
            </section>
        </section>
    </main>
</html>