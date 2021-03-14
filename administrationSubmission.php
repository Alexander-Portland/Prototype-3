<?php

session_start();

//The username and password of the user is extracted for account check
$nameCheck = $_SESSION['username'];
$passCheck = $_SESSION['password'];

//The first connection to the database
$con = mysqli_connect('localhost','root','');

mysqli_select_db($con,'demo');

//The system checks if the user's username and password are the same as an existing admin account
$teacherPick = "select admin_ID, forename, surname from admin where admin_username = '$nameCheck' && admin_password = '$passCheck' ";


$resultStudent = mysqli_query($con,$teacherPick);
$numStudent = mysqli_num_rows($resultStudent);

//The ID, first name and last name of the account is extracted
$row = $resultStudent->fetch_assoc();
$ID = $row['admin_ID'];
$Fname = $row['forename'];
$Lname = $row['surname'];

//If the user account does not match an existing admin account then the page redirects the user to the index page
if($numStudent == 0){
    $_SESSION['username'] = "";
    header('location:index.php');
}

//$dbh is used for making changes to the database
$dbh = new PDO("mysql:host=localhost;dbname=demo","root","");

//This function is executed when the yes button of the add account function on administration.php is pressed
if(isset($_POST['sendNewClass'])){
    //The new class name and description inputs are extracted and filtered
    $classTitle = htmlspecialchars($_POST['classNameInput'],ENT_COMPAT);
    $classDescription = htmlspecialchars($_POST['classDescriptionInput'],ENT_COMPAT);

    //The system checks if there are any classes that exist with the same title 
    $classQuery = "select * from classdetails where class_title = '$classTitle'";
    $resultclass = mysqli_query($con,$classQuery);
    $numclass = mysqli_num_rows($resultclass);

    //if there are any pre existing classes the same title then the add class is rejected and the error message below is displayed
    if($numclass >= 1){
        $errorTitle = "Adding class rejected";
        $errorMessage = "There is already a class with this name on the system";
    }
    //if there is not a pre exiting class with the same title then the add class is accepted and a new class is inserted into classdetails
    else{
        $stmt = $dbh->prepare("insert into classdetails values('',?,?)");
        $stmt->bindParam(1,$classTitle);
        $stmt->bindParam(2,$classDescription);
        $stmt->execute();
        //once completed the page will return the user to the admin homepage
        header("Refresh:0; administration.php");
    }
    }

//this function is only executed when the yes button of the delete class section on administration.php is pressed
if(isset($_POST['btnDelete'])){
    //The class UD and title are extracted
    $deleteID = $_POST['classDeleteID'];
    $deleteTitle = $_POST['classDeleteTitle'];
    
    //The system searches for any classes with the same ID and title as the one the user specified in administration.php
    $deleteFind = "select class_id from classdetails where class_id = '$deleteID' && class_title = '$deleteTitle'";
    $resultClassFind = mysqli_query($con,$deleteFind);
    $numDeleteResult = mysqli_num_rows($resultClassFind);
    
    //if there is an existing class the user specififed the system will delete the specified class
    if($numDeleteResult == 1){
        $classDelete = "delete from classdetails where class_id = '$deleteID'";
        $classDeleteQuery = mysqli_query($con,$classDelete);
        //Once deletion is complete the system will return the user to the admin homepage
        header("Refresh:0; administration.php");
    }
    //If there is not an exisitng class the user specified then the deletion process is aborted and the error message below is displayed
    else{
        $errorTitle = "Delete class rejected";
        $errorMessage = "The class you are attempting to delete a different class from the one you selected or a class that does not exist";
        
    }
}

//this function is only executed when the yes button of the update class section is pressed
if(isset($_POST['search'])){
    //the class ID of the specified class is extracted
    $classID = $_POST['classUpdateID'];
    //The updated class title and description are extracted and filtered
    $classTitleSearch = htmlspecialchars($_POST['classUpdate'],ENT_COMPAT);
    $classDescriptionSearch = htmlspecialchars($_POST['classDescriptionUpdate'],ENT_COMPAT);

    //The system checks if there are any existing accounts with the same class ID the user specified
    $classFindSearch = "select class_id from classdetails where class_id = '$classID'";
    $resultClassFind = mysqli_query($con,$classFindSearch);
    $numClassResult = mysqli_num_rows($resultClassFind);
    
    //If the system found an existing class with the specified ID then the update is executed
    if($numClassResult == 1){
        $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");
        $update = $dbh->prepare("update classdetails set class_id = ?, class_title = ?, description = ? where class_id = $classID");
        $update->bindParam(1,$classID);
        $update->bindParam(2,$classTitleSearch);
        $update->bindParam(3,$classDescriptionSearch);
        $update->execute(); 
        //When the update is complete the system will redirect the user to the admin homepage  
        header("Refresh:0; administration.php");
    }
    //If the system does not find the specified class then the error message below is displayed
    else{
        $errorTitle = "Updating class rejected";
        $errorMessage = "The class you are atempting to update does not exist on the system";
    }
}

//This function will only be executed when the user presses the yes button of the add account section on administration.php
if(isset($_POST['accountAdd'])){
    //the account first name, last name, username, password and account type are extracted and filtered
    $accountFName = htmlspecialchars($_POST['accountFirstName'],ENT_COMPAT);
    $accountLName = htmlspecialchars($_POST['accountLastName'],ENT_COMPAT);
    $accountUserName = htmlspecialchars($_POST['accountUserName'],ENT_COMPAT);
    $accountPassword = htmlspecialchars($_POST['accountPassword'],ENT_COMPAT);
    $accountType = htmlspecialchars($_POST['accountTypeSelect'],ENT_COMPAT);
    //The system checks if the new account type was student
    if($accountType == "Student"){
        //The system then checks if there is a student account with the same username
        $accountQuery = "select * from studentdetails where student_username = '$accountUserName'";
        $accountQueryResult = mysqli_query($con,$accountQuery);
        $numAccountQueryResult = mysqli_num_rows($accountQueryResult);

        //If the system finds an existing student account with the same username the add account is aborted and the error message below is displayed
        if($numAccountQueryResult >= 1){
            $errorTitle = "Adding student account rejected";
            $errorMessage = "There is already an existing student account with that username";
        }
        //If the system does not find an existing student account with the same username the new account is inserted into studentdetails
        else{
            $stmt = $dbh->prepare("insert into studentdetails values('',?,?,?,?)");
            $stmt->bindParam(1,$accountFName);
            $stmt->bindParam(2,$accountLName);
            $stmt->bindParam(3,$accountUserName);
            $stmt->bindParam(4,$accountPassword);
            $stmt->execute();
            //When the insert query is complete the system redirects the user to the admin homepage
            header("Refresh:0; administration.php");
        }
    }
    //The system checks if the new account type was teacher
    elseif($accountType == "Teacher"){
        
        //The system checks if there is an existing teacher account with the same username
        $accountQuery = "select * from teacherdetails where teacher_username = '$accountUserName'";
        $accountQueryResult = mysqli_query($con,$accountQuery);
        $numAccountQueryResult = mysqli_num_rows($accountQueryResult);

        //If there is an existing teacher account with the same username the add account is aborted and the error message below is displayed
        if($numAccountQueryResult >= 1){
            $errorTitle = "Adding teacher account rejected";
            $errorMessage = "There is already an existing teacher account with that username";
        }
        //If there is not an existing account with the same username then the new account is inserted into the teacherdetails table
        else{
            $stmt = $dbh->prepare("insert into teacherdetails values('',?,?,?,?)");
            $stmt->bindParam(1,$accountFName);
            $stmt->bindParam(2,$accountLName);
            $stmt->bindParam(3,$accountUserName);
            $stmt->bindParam(4,$accountPassword);
            $stmt->execute();
            //when the insert query is complete the system redirects the user to the admin homepage
            header("Refresh:0; administration.php");
        }
    }
    //The system checks if the new account type was Admin
    elseif($accountType == "Admin"){
        
        //the system checks if there are any existing admin accounts with the same username
        $accountQuery = "select * from admin where admin_username = '$accountUserName'";
        $accountQueryResult = mysqli_query($con,$accountQuery);
        $numAccountQueryResult = mysqli_num_rows($accountQueryResult);

        //If there are any existing admin accounts with the same username the add account is aborted and the error message below is displayed
        if($numAccountQueryResult >= 1){
            $errorTitle = "Adding admin account rejected";
            $errorMessage = "There is already an existing admin account with that username";
        }
        //If there is not an existing admin account with the same username then the new account is inserted into the admin table
        else{
            $stmt = $dbh->prepare("insert into admin values('',?,?,?,?)");
            $stmt->bindParam(1,$accountFName);
            $stmt->bindParam(2,$accountLName);
            $stmt->bindParam(3,$accountUserName);
            $stmt->bindParam(4,$accountPassword);
            $stmt->execute();
            //when the insert query is complete the user is redirected to the admin homepage
            header("Refresh:0; administration.php");
        }
    }
    //If the user has specified a non existent account type then the add account is aborted and the error message below are displayed
    else{
        $errorTitle = "Error: selected unknown account type";
        $errorMessage = "It appears you have attempted to add an account type that does not exist";
    }
}
?>
<!--This code is only displayed if any of the called functions in administration.php is aborted or rejected-->
<html>
    <head>
        <!--This rejection page is linked to a single css and javascript page like the other rejection pages -->
        <title>Administration submission</title>
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
        <!--The rejection title and description is displayed in this section-->
        <section class = "centerPosClass">
            <section class = "helpContent">
            <!--The rejection display returns the user to the admin homepage when they press the return button-->
                <form action="administration.php">
                    <button class= "expandButton button">Return</button>
                </form>
                <!--The error title and message are changed depending on the error that was invoked-->
                <label class = "loginLabel"><?php echo $errorTitle ?> </label>
                <p><?php echo $errorMessage ?> </p>
            </section>
        </section>
    </main>
</html>