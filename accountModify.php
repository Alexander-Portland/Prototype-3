<?php
    session_start();

    //This is the first connection to the database
    $con = mysqli_connect('localhost','root','');
    
    mysqli_select_db($con,'demo');

    //the username of the session are extracted
    $nameCheck = $_SESSION['username'];
    
    //the query below checks if there are any admin accounts with the same username as the session username
    $teacherPick = "select * from admin where admin_username = '$nameCheck' ";

    $resultTeacher = mysqli_query($con,$teacherPick);
    $numStudent = mysqli_num_rows($resultTeacher);

    //if the system finds there are no admin accounts with the same username then the page will redirect the user to the index page
    if($numStudent == 0){
        $_SESSION['username'] = "";
        header('location:index.php');
    }
      
    //$dbh variable is used as a connection to help modify the account details
    $dbh = new PDO("mysql:host=localhost;dbname=demo","root","");

    //this function is only executed when the user presses the yes button of the delete section on viewAccount.php
    if(isset($_POST['btnDeleteAccount'])){
        //the account ID and type are extracted and filtered
        $accountID = htmlspecialchars($_POST['accountDeleteID'],ENT_COMPAT);
        $accountType = htmlspecialchars($_POST['accountDeleteType'],ENT_COMPAT);
        //The system checks if the account type is a student
        if($accountType == "Student"){
            //The system deletes the specified account and return the user to the admin homepage
            $accountQueryDelete = "delete from studentdetails where student_id = '$accountID'";
            $accountQueryDeleteExecute = mysqli_query($con,$accountQueryDelete);
            header("Refresh:0; administration.php");
        }
        //The system checks if the account type is a teacher
        elseif($accountType == "Teacher"){
            //The system deletes the specified account and return the user to the admin homepage
            $accountQueryDelete = "delete from teacherdetails where teacher_id = '$accountID'";
            $accountQueryDeleteExecute = mysqli_query($con,$accountQueryDelete);
            header("Refresh:0; administration.php");
        }
        //The system checks if the account type is a admin
        elseif($accountType == "Admin"){
            //The system deletes the specified account and return the user to the admin homepage
            $accountQueryDelete = "delete from admin where admin_id = '$accountID'";
            $accountQueryDeleteExecute = mysqli_query($con,$accountQueryDelete);
            header("Refresh:0; administration.php");
        }
        //If the user selected a invalid account type then the error message below is displayed
        else{
            $errorTitle = "Delete account rejected";
            $errorStatement = "You are attempting to delete an account whose type that does not exist";
        }
    }
    //this function is only executed when the yes button of the update account section is pressed
    if(isset($_POST['btnUpdateAccount'])){
        //the account details are extracted and filtered
        $accountID = htmlspecialchars($_POST['accountUpdateID'],ENT_COMPAT);
        $accountType = htmlspecialchars($_POST['accountUpdateType'],ENT_COMPAT);
        $updateFirstName = htmlspecialchars($_POST['Fname'],ENT_COMPAT);
        $updateLastName = htmlspecialchars($_POST['Lname'],ENT_COMPAT);
        $updateUsername = htmlspecialchars($_POST['Username'],ENT_COMPAT);
        $updatePassword = htmlspecialchars($_POST['Password'],ENT_COMPAT);

        //if the account type is a student then the update is executed and the page redirects the user back to the admin homepage
        if($accountType == "Student"){
            $update = $dbh->prepare("update studentdetails set  forname = ?, surname = ? ,student_username = ?, student_password = ? where student_id = $accountID");
            $update->bindParam(1,$updateFirstName);
            $update->bindParam(2,$updateLastName);
            $update->bindParam(3,$updateUsername);
            $update->bindParam(4,$updatePassword);
            $update->execute();
            header("Refresh:0; administration.php");
        }
        //if the account type is a teacher then the update is executed and the page redirects the user back to the admin homepage
        elseif($accountType == "Teacher"){
            $update = $dbh->prepare("update teacherdetails set  teacher_forname = ?, teacher_surname = ? ,teacher_username = ?, teacher_password = ? where teacher_id = $accountID");
            $update->bindParam(1,$updateFirstName);
            $update->bindParam(2,$updateLastName);
            $update->bindParam(3,$updateUsername);
            $update->bindParam(4,$updatePassword);
            $update->execute();
            header("Refresh:0; administration.php");
        }
        //if the account type is a admin then the update is executed and the page redirects the user back to the admin homepage
        elseif($accountType == "Admin"){
            $update = $dbh->prepare("update admin set  forename = ?, surname = ? ,admin_username = ?, admin_password = ? where admin_ID = $accountID");
            $update->bindParam(1,$updateFirstName);
            $update->bindParam(2,$updateLastName);
            $update->bindParam(3,$updateUsername);
            $update->bindParam(4,$updatePassword);
            $update->execute();
            header("Refresh:0; administration.php");
        }
        //If the user is trying to update an account with a type that doesnt exist then the update is aborted and the error message below is displayed
        else{
            $errorTitle = "Update account rejected";
            $errorStatement = "You are attempting to update an account whose type that does not exist";
        }

    }
    //the function below is executed when the user presses the yes button of the add class section
    if(isset($_POST['btnAddClassAccount'])){
        //The account ID, picked class and account type are extracted and filtered
        $accountID = htmlspecialchars($_POST['accountAddClassID'],ENT_COMPAT);
        $classPick = htmlspecialchars($_POST['lessonSelect'],ENT_COMPAT);
        $accountType = htmlspecialchars($_POST['accountAddType'],ENT_COMPAT);
        //the system checks what type of account was selected
        if($accountType == "Student"){
            //The system checks if the student account has already been assigned the picked class
            $checkClass = "select * from studentdetails_classdetails where student_id = $accountID && class_id = $classPick";
            $checkClassExecute = mysqli_query($con,$checkClass);
            $numCheckClassExecute = mysqli_num_rows($checkClassExecute);
            //If the system finds that the account has been assigned to the selected account the error message below is displayed
            if($numCheckClassExecute >= 1){
                $errorTitle = "Adding class to account rejected";
                $errorStatement = "This account has already been assigned this class";
            }
            //If the selected account has not already been assigned the class then the new class assignment is inserted and the page redirects the user back to the admin homepage
            else{
                $stmt = $dbh->prepare("insert into studentdetails_classdetails values('',?,?)");
                $stmt->bindParam(1,$accountID);
                $stmt->bindParam(2,$classPick);
                $stmt->execute();
                header("Refresh:0; administration.php");
            }
        }
        //the system checks what type of account was selected
        elseif($accountType == "Teacher"){
            //The system checks if a teacher account has already been assigned the picked class
            $checkClass = "select * from teacherdetails_classdetails where teacher_id = $accountID && class_id = $classPick";
            $checkClassExecute = mysqli_query($con,$checkClass);
            $numCheckClassExecute = mysqli_num_rows($checkClassExecute);
            //If the system finds that the account has been assigned to the selected account the error message below is displayed
            if($numCheckClassExecute >= 1){
                $errorTitle = "Adding class to account rejected";
                $errorStatement = "This account has already been assigned this class";
            }
            //If the selected account has not already been assigned the class then the new class assignment is inserted and the page redirects the user back to the admin homepage
            else{
                $stmt = $dbh->prepare("insert into teacherdetails_classdetails values('',?,?)");
                $stmt->bindParam(1,$accountID);
                $stmt->bindParam(2,$classPick);
                $stmt->execute();
                header("Refresh:0; administration.php");
            }
        }
        //If the user has picked an account type that does require assigned classes then the error message below is displayed
        else{
            $errorTitle = "Update account rejected";
            $errorStatement = "You are attempting to add a class to an account that does not have classes assigned to them";
        }
    }
    //This function is only executed when the yes button of the remove class section is pressed
    if(isset($_POST['btnRemoveClass'])){
        //The account ID, class ID and student type are extracted and filtered
        $accountID = htmlspecialchars($_POST['accountRemoveClassID'],ENT_COMPAT);
        $classID = htmlspecialchars($_POST['removeClassID'],ENT_COMPAT);
        $studentType = htmlspecialchars($_POST['removeClassIDAccountType'],ENT_COMPAT);

        //if the account type is valid then the selected assigned class is removed and the page redirects to the admin homepage
        if($studentType == "Student"){
            $removeClassQuery = "delete from studentdetails_classdetails where student_id = '$accountID' && class_id = '$classID'";
            $removeClassQueryExecute = mysqli_query($con,$removeClassQuery);
            header("Refresh:0; administration.php");
        }
        //if the account type is valid then the selected assigned class is removed and the page redirects to the admin homepage
        elseif($studentType == "Teacher"){
            $removeClassQuery = "delete from teacherdetails_classdetails where teacher_id = '$accountID' && class_id = '$classID'";
            $removeClassQueryExecute = mysqli_query($con,$removeClassQuery);
            header("Refresh:0; administration.php");
        }
        //if the account type is invalid then the error message below is displayed
        else{
            $errorTitle = "Remove class rejected";
            $errorStatement = "You are attempting to delete a class from an account type that does not qualify a class assignment";
        }

    }

    //the function below is only executed when the yes button of the class update section is pressed
    if(isset($_POST['btnUpdateAssignedClass'])){
        //the account ID, old class ID, new class ID and account type are extracted and filtered
        $accountID = htmlspecialchars($_POST['accountModifyClassID'],ENT_COMPAT);
        $oldClassID = htmlspecialchars($_POST['oldClassID'],ENT_COMPAT);
        $newClassID = htmlspecialchars($_POST['lessonSelect'],ENT_COMPAT);
        $modifyType = htmlspecialchars($_POST['accountModifyType'],ENT_COMPAT);

        //If the selected account type is student then the update is executed and the page redirects the user back to the admin homepage
        if($modifyType == "Student"){
            $update = $dbh->prepare("update studentdetails_classdetails set class_id = ? where student_id = $accountID && class_id = $oldClassID");
            $update->bindParam(1,$newClassID);
            $update->execute();
            header("Refresh:0; administration.php");   
        }
        //If the selected account type is teacher then the update is executed and the page redirects the user back to the admin homepage
        elseif($modifyType == "Teacher"){
            $update = $dbh->prepare("update teacherdetails_classdetails set class_id = ? where teacher_id = $accountID && class_id = $oldClassID");
            $update->bindParam(1,$newClassID);
            $update->execute();
            header("Refresh:0; administration.php"); 
        }
        //If the selected account type is invalid then the update is aborted and the error message below is executed
        else{
            $errorTitle = "Updating assigned classes";
            $errorStatement = "You are attempting to delete a class from an account type that does not qualify a class assignment";
        }

    }
    
    
?>
<!--The code below is only executed if any of the accountModify processes are rejected or aborted -->
<html>
    <head>
        <!--The rejection page uses the same css and javascript page as the other rejection pages -->
        <title>Modify Account</title>
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
        <!--The section below displays the error message -->
        <section class = "centerPosClass">
            <section class = "helpContent">
            <!--When the user presses the return button they will be redirected to the admin homepage -->
                <form action="administration.php">
                    <button class= "expandButton button">Return</button>
                </form>
                    <!--The error message title and description change depending on the error invoked with by the user -->
                    <label class = "loginLabel"><?php echo $errorTitle ?></label>
                    <p><?php echo $errorStatement ?></p>
            </section>
        </section>
    </main>
</html>