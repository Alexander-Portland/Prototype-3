<?php
    session_start();

    //The username of the session is extracted
    $nameCheck = $_SESSION['passName'];
    
    //this is the first connection to the database
    $con = mysqli_connect('localhost','root','');
    
    mysqli_select_db($con,'demo');

    //this query checks if there is a admin account with the same session username
    $teacherPick = "select * from admin where admin_username = '$nameCheck' ";

    $resultTeacher = mysqli_query($con,$teacherPick);
    $numStudent = mysqli_num_rows($resultTeacher);

    //if there is not an admin account with the same username then the page redirects the user back to the index page
    if($numStudent == 0){
        $_SESSION['username'] = "";
        header('location:index.php');
    }

    //The function below is only executed when the yes button of the search section on administration.php is pressed
    if(isset($_POST['searchAccountBtn'])){
        
        //The searched first and last name and the searched account type are extracted and filtered
        $firstNameInput = htmlspecialchars($_POST['fNameSearch'],ENT_COMPAT);
        $lastNameInput = htmlspecialchars($_POST['lNameSearch'],ENT_COMPAT);
        $accountType = htmlspecialchars($_POST['searchAccountType'],ENT_COMPAT);

        //The system checks if the selected account type is a student
        if($accountType == "Student"){
            //The system then searches for any student account with the same first and last name the user searched for
            $accountQuery = "select * from studentdetails where forname = '$firstNameInput' && surname = '$lastNameInput'";
            $accountQueryResult = mysqli_query($con,$accountQuery);
            $numAccountQueryResult = mysqli_num_rows($accountQueryResult);
            $classSearchName = "students";
        }
        //The system checks if the selected account type is a teacher
        elseif($accountType == "Teacher"){
            //The system then searches for any teacher account with the same first and last name the user searched for
            $accountQuery = "select * from teacherdetails where teacher_forname = '$firstNameInput' && teacher_surname = '$lastNameInput'";
            $accountQueryResult = mysqli_query($con,$accountQuery);
            $numAccountQueryResult = mysqli_num_rows($accountQueryResult);
            $classSearchName = "teachers";
        }
        //The system checks if the selected account type is a admin
        elseif($accountType == "Admin"){
            //The system then searches for any admin account with the same first and last name the user searched for
            $accountQuery = "select * from admin where forename = '$firstNameInput' && surname = '$lastNameInput'";
            $accountQueryResult = mysqli_query($con,$accountQuery);
            $numAccountQueryResult = mysqli_num_rows($accountQueryResult);
            $classSearchName = "admin";
        }
    }
    //If the user has not selected a valid account type then the page redirects the user back to the index page
    else{
        $classSearchName = "none";
        header("Refresh:0; administration.php");
    }
?>
<html>  
    <head>
        <!--The viewAccounts page uses the same css and javascript page as the other web pages-->
        <title>Searched Account</title>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="mystyle.css">
        <style>
            <?php include 'mystyle.css'; ?>
        </style>
        <script src="pageInteraction.js"></script>
    </head>
    <main>

    <!--This section gives the user the ability to add classes to a searched account-->
    <section id = "accountAddClass" class = "centerPosClass hidePost">
        <section class = "classPosts">
        <p class = "teacherInteractionBoxTitle">Update account</p>
            <form action="accountModify.php" method="post">
                <!--The account ID and type are kept hidden from the user-->
                <input type="text" name ="accountAddClassID" class = "hidePost"><br>
                <input type="text" name ="accountAddType" class = "hidePost"><br>
                <!--The select bar uses a php search to appened every class name and their ID to the drop bar-->
                <select name = "lessonSelect">
                    <?php
                    //All classes are searched for
                    $classListExtract = "select * from classdetails";
                    $classListExtractExecute = mysqli_query($con,$classListExtract);
                    while($rowClassExtract = $classListExtractExecute->fetch_assoc()): ?>
                        <?php
                            //For each class extracted their ID and title are appended to the select bar
                            $classExtractID = $rowClassExtract['class_id'];
                            $classExtractTitle = $rowClassExtract['class_title'];
                            echo'<option value = "'.$classExtractID.'">'.$classExtractTitle.'</option>';
                        ?>
                    <?php endwhile; 
                    ?>
                </select>
                
                <b><p>Are you sure you want to add this lesson?</p></b>
                <!--If the user presses the yes button the add class is submitted to accountModify.php-->
                <button name="btnAddClassAccount" class = "button buttonGreen">Yes</button>
                <!--If the user presses no then the add class is aborted and the section is hidden-->
                <button onclick="viewAccountCloser()" class = "button buttonRed" type = "button">No</button>
            </form>
        </section>
    </section>

    <!--This section gives the user the ability to remove an assigned class from a selected account-->
    <section id = "removeclass" class = "centerPosClass hidePost">
            <section class = "classPosts">
            <p class = "teacherInteractionBoxTitle">Remove lesson from account</p>
                <!--The form below contains the hidden class ID and account ID as well as a yes and no button to confirm or abort the process-->
                <form action="accountModify.php" method="post">
                    <input type="text" name ="accountRemoveClassID" class = "hidePost"><br>
                    <input type="text" name ="removeClassID" class = "hidePost"><br>
                    <input type="text" name ="removeClassIDAccountType" class = "hidePost"><br>
                    <label class = "displayInline"><b>Class Title: </b></label><p id = "classRemoveTitle" class = "displayInline"></p><br>
                    <b><p>Are you sure you want to remove this lesson?</p></b>
                    <!--If the user presses the yes button then the deletion process is submitted to accountModify.php-->
                    <button name="btnRemoveClass" class = "button buttonGreen">Yes</button>
                    <!--If the user presses the no button then the deletion process isaborted and the section is hidden-->
                    <button onclick="viewAccountCloser()" class = "button buttonRed" type = "button">No</button>
                </form>
            </section>
    </section>
    
    <!--This section allows the user to re assign one selected class of the searched account-->
    <section id = "updateAssignedClass" class = "centerPosClass hidePost">
        <section class = "classPosts">
        <p class = "teacherInteractionBoxTitle">Modify assigned class</p>
            <!--The form below will hide the account ID, the old class assignment ID and the account type. It then has a drop down bar for selecting the new class-->
            <form action="accountModify.php" method="post">
                <input type="text" name ="accountModifyClassID" class = "hidePost"><br>
                <input type="text" name ="oldClassID" class = "hidePost"><br>
                <input type="text" name ="accountModifyType" class = "hidePost"><br>
                <label class = "displayInline"><b>Old class: </b></label><p id = "displayOldClassTitle" class = "displayInline"></p><br>
                <label class = "displayInline"><b>New Class: </b></label>
                <!--The select bar will contain the names and IDs of every class on the system-->
                <select name = "lessonSelect">
                <?php
                //This query will search for every class and its details
                $classListExtract = "select * from classdetails";
                $classListExtractExecute = mysqli_query($con,$classListExtract);
                //For each class extracted from the query the class name and ID are extacted and added to the select bar
                while($rowClassExtract = $classListExtractExecute->fetch_assoc()): ?>
                    <?php
                        $classExtractID = $rowClassExtract['class_id'];
                        $classExtractTitle = $rowClassExtract['class_title'];
                        //class title and ID are appended to the select bar
                        echo'<option value = "'.$classExtractID.'">'.$classExtractTitle.'</option>';
                    ?>
                <?php endwhile; 
                ?>
            </select>  
                <b><p>Are you sure you want to modify this lesson assignment?</p></b>
                <!--If the user presses the yes button then the update to the class assignement is submitted to accountModify.php -->
                <button name="btnUpdateAssignedClass" class = "button buttonGreen">Yes</button>
                <!--If the user presses the no button then the uupdate to the class assignement is aborted and the section is hidden -->
                <button onclick="viewAccountCloser()" class = "button buttonRed" type = "button">No</button>
            </form>
        </section>
    </section>

    <!--This section gives the user the ability to update the account details of a selected account-->
    <section id = "accountUpdate" class = "centerPosClass hidePost">
        <section class = "classPosts">
            <p class = "teacherInteractionBoxTitle">Update account</p>
            <!--The form contains input field for the firt name, last name, username and password which will have the previoous details loaded in-->
            <form action="accountModify.php" method="post">
                <input type="text" name ="accountUpdateID" class = "hidePost"><br>
                <input type="text" name ="accountUpdateType" class = "hidePost"><br>
                <label class = "displayInline"><b>First Name: </b></label><input type="text" name="Fname" class = "inputButton" required><br>
                <label class = "displayInline"><b>Last Name: </b></label><input type="text" name="Lname" class = "inputButton" required><br>
                <label class = "displayInline"><b>User Name: </b></label><input type="text" name="Username" class = "inputButton" required><br>
                <label class = "displayInline"><b>Password: </b></label><input type="text" name="Password" class = "inputButton" required><br>
                <b><p>Are you sure you want to update this account?</p></b>
                <!--If the user presses the yes button then the update for the account is submitted to accountModify.php-->
                <button name="btnUpdateAccount" class = "button buttonGreen">Yes</button>
                <!--If the user presses the no button then the update is aborted and the section is hidden-->
                <button onclick="viewAccountCloser()" class = "button buttonRed" type = "button">No</button>
            </form>
        </section>
    </section>

    <!--This section is used for deleting a selected account -->
    <section id = "accountDelete" class = "centerPosClass hidePost">
        <section class = "classPosts">
            <p class = "teacherInteractionBoxTitle">Delete account</p>
            <!--The form below will contain the user details of the account they wish to delete-->
            <form action="accountModify.php" method="post">
                <!--The account ID and type of the selected account are kept hidden -->
                <input type="text" name ="accountDeleteID" class = "hidePost"><br>
                <input type="text" name ="accountDeleteType" class = "hidePost"><br>
                <label class = "displayInline"><b>First Name: </b></label><p id = "deleteOutputFName" class = "displayInline"></p><br>
                <label class = "displayInline"><b>Last Name: </b></label><p id = "deleteOutputLName" class = "displayInline"></p><br>
                <label class = "displayInline"><b>User Name: </b></label><p id = "deleteOutputUserName" class = "displayInline"></p><br>
                <label class = "displayInline"><b>Password: </b></label><p id = "deleteOutputPassword" class = "displayInline"></p><br>
                <label class = "displayInline"><b>Account Type: </b></label><p id = "deleteOutputType" class = "displayInline"></p><br>
                <b><p>Are you sure you want to Remove this account?</p></b>
                <!--If the yes presses the yes button then the delete account will be submitted to accountModify.php-->
                <button name="btnDeleteAccount" class = "button buttonGreen">Yes</button>
                <!--If the user selects no then the delete account will be aborted and the section will be hidden-->
                <button  onclick="viewAccountCloser()" class = "button buttonRed" type = "button">No</button>
            </form>
        </section>
    </section>

        <!--The section below is resposible for displaying all of the account details and assigned classes of the accounts that were searched for by the user-->
        <section id = "result" class = "centerPosClass">
            <section class = "classPosts">
            <p class = "teacherInteractionBoxTitle">Account details of <?php echo $firstNameInput ?> <?php echo $lastNameInput?></p>
            <!--The form below allows the user to presses a return button which will taken them back to the admin homepage-->
            <form action="administration.php" method="post">
                <br><br><button class = "button expandButton">Return to Admin Page</button><br><br>
            </form>
                <!--The PHP below is responsible for extracting all of the account and class information on the searched student/students -->
                <?php
                    //The system checks what type of account was searched for
                    if($accountType == "Student" || $accountType == "Teacher" || $accountType == "Admin"){
                        //The system checks if there are any accounts with the specified first and last name of the search input 
                        if($numAccountQueryResult >= 1){
                        //The while loop below cycles through every searched account
                        while($rowClass = $accountQueryResult->fetch_assoc()): ?> 
                            <?php
                                //The system checks if the search was for student type accounts
                                if($classSearchName == "students"){
                                    //The student ID of the searched account is extracted
                                    $selectedID = $rowClass['student_id'];

                                    //The query below searches for student accounts with the same ID as the selected ID
                                    $studentQuery = "select * from studentdetails where student_id = $selectedID";
                                    $studentQueryExecute = mysqli_query($con,$studentQuery);
                                    $queryCount = mysqli_num_rows($studentQueryExecute);
                                    $studentDetails = $studentQueryExecute->fetch_assoc();

                                    //The student username and password are extracted
                                    $selectedUsername = $studentDetails['student_username'];
                                    $selectedPassword = $studentDetails['student_password'];
                                }
                                //The system checks if the search was for teacher type accounts
                                elseif($classSearchName == "teachers"){
                                    //The teacher ID of the searched account is extracted
                                    $selectedID = $rowClass['teacher_id'];

                                    //The query below searches for teacher account with the same ID as the selected ID
                                    $studentQuery = "select * from teacherdetails where teacher_id = $selectedID";
                                    $studentQueryExecute = mysqli_query($con,$studentQuery);
                                    $queryCount = mysqli_num_rows($studentQueryExecute);
                                    $studentDetails = $studentQueryExecute->fetch_assoc();

                                    //The teacher username and password are extracted
                                    $selectedUsername = $studentDetails['teacher_username'];
                                    $selectedPassword = $studentDetails['teacher_password'];
                                }
                                //The system checks if the search was for admin type accounts
                                elseif($classSearchName == "admin"){
                                    //The admin ID of the searched account is extracted
                                    $selectedID = $rowClass['admin_ID'];

                                    //The query below searches for admin account with the same ID as the selected ID
                                    $studentQuery = "select * from admin where admin_ID = $selectedID";
                                    $studentQueryExecute = mysqli_query($con,$studentQuery);
                                    $queryCount = mysqli_num_rows($studentQueryExecute);
                                    $studentDetails = $studentQueryExecute->fetch_assoc();

                                    //The admin username and password are extracted
                                    $selectedUsername = $studentDetails['admin_username'];
                                    $selectedPassword = $studentDetails['admin_password'];
                                }

                                //randomly generated IDs are needed to identify each displayed detail of the outputted account
                                $accountIDPick = rand();
                                $accountFName = rand();
                                $accountLName = rand();
                                $accountUsername = rand();
                                $accountPassword = rand();
                                $accountTypePick = rand();
                            
                                //The section below displays all of the account details of each searched account using the extracted details and generated IDs
                                echo'<br><section>';
                                    echo '<p id = "'.$accountIDPick.'" class = "hidePost">'.$selectedID.'</p>';
                                    echo'<p class = "displayInline"><b>First name: </b></p> <p id = "'.$accountFName.'" class = "displayInline">'.$firstNameInput.'</p><br>';
                                    echo'<p class = "displayInline"><b>Last name: </b></p> <p id = "'.$accountLName.'" class = "displayInline">'.$lastNameInput.'</p><br>';
                                    echo'<p class = "displayInline"><b>Username: </b></p> <p id = "'.$accountUsername.'" class = "displayInline">'.$selectedUsername.'</p><br>';
                                    echo'<p class = "displayInline"><b>Password: </b></p> <p id = "'.$accountPassword.'" class = "displayInline">'.$selectedPassword.'</p><br>';
                                    echo'<p class = "displayInline"><b>Account Type: </b></p> <p id = "'.$accountTypePick.'"  class = "displayInline">'.$accountType.'</p><br>';
                                    echo '<button onclick = "deleteAccountSend('.$accountIDPick.','.$accountTypePick.','.$accountFName.','.$accountLName.','.$accountUsername.','.$accountPassword.')" class="button">Delete Account</button>';
                                    echo '<button onclick ="updateAccountSend('.$accountIDPick.','.$accountTypePick.','.$accountFName.','.$accountLName.','.$accountUsername.','.$accountPassword.')" class="button">Update Account</button>';
                                    echo '<button onclick ="addAccountSend('.$accountIDPick.','.$accountTypePick.')" class="button">Add Classes to account</button>';
                                echo'</section>';

                                //This section is used to display all of the classes that have been assigned with the account being assigned
                                echo'<br><section>';
                                    echo'<p class = "teacherInteractionBoxTitle">Assigned classes</p>';
                                    //The system checks what type of account is being displayed
                                    if($classSearchName == "students"){
                                        //the query below is used to extract the classes that the displayed student is assigned to
                                        $classSelect = "select class_id from studentdetails_classdetails where student_id = $selectedID";
                                        $classQuery = mysqli_query($con,$classSelect);
                                        $numClassQueryResult = mysqli_num_rows($classQuery);
                                    }
                                    //The system checks what type of account is being displayed
                                    elseif($classSearchName == "teachers"){
                                        //the query below is used to extract the classes that the displayed teacher is assigned to
                                        $classSelect = "select class_id from teacherdetails_classdetails where teacher_id = $selectedID";
                                        $classQuery = mysqli_query($con,$classSelect);
                                        $numClassQueryResult = mysqli_num_rows($classQuery);
                                    }
                                    //The system checks what type of account is being displayed
                                    elseif($classSearchName == "admin"){
                                        //if the account type is an admin then the system designates that there are noassigned classes to the account
                                        $numClassQueryResult = 0;
                                    }
                                    //
                                    if($numClassQueryResult >= 1){
                                        //If there are any assigned classes to the displayed account the while loop below cycles through each class
                                        while($classRow = $classQuery->fetch_assoc()): ?> 
                                            <?php
                                                //The class ID of the current class is extracted
                                                $classID = $classRow['class_id'];
                                                
                                                //randomly generated IDs are needed for the displayed class details
                                                $selectClassID = rand();
                                                $classTitleID = rand();
                                                
                                                //Teh query below is used for extracting the class details of the class being displayed
                                                $classExtract = "select * from classdetails where class_id = $classID";
                                                $classExtractExecution = mysqli_query($con,$classExtract);
                                                $numClassExtractExecution = mysqli_num_rows($classExtractExecution);
                                                $classExtractRow = $classExtractExecution->fetch_assoc();
                                                
                                                //The class title of the displayed class is extracted
                                                $className = $classExtractRow['class_title'];

                                                //If the system finds that there are assigned classes to the displayed account then the section below displays the class detail and interaction buttons
                                                if($numClassExtractExecution >= 1){
                                                    echo '<br><section class = "classOutliner">';
                                                        echo '<input type="text" id = "'.$selectClassID.'" value = "'.$classID.'" class = "hidePost"><br>';
                                                        echo '<label class = "displayInline"><b>Class Name: </b></label><p id = "'.$classTitleID.'" class = "displayInline">'.$className.'</p><br>';
                                                        echo '<button onclick ="removeAccountClassSend('.$accountIDPick.','.$selectClassID.','.$accountTypePick.','.$classTitleID.')" class="button">Remove</button>';
                                                        echo '<button onclick ="updateClassAssign('.$accountIDPick.','.$selectClassID.','.$accountTypePick.','.$classTitleID.')" class="button">Update</button>';
                                                    echo'</section>';
                                                }
                                                //If there are not any assigned classes to the account then the message below is displayed
                                                else{
                                                    echo '<section class = "classOutliner">';
                                                        echo '<p>This account has not been assigned any classes</p>';
                                                    echo'</section>';
                                                }
                                            ?>
                                        <?php endwhile;
                                        //For each displayed account there is a bar seperator to clear denote the end of one account from another
                                        echo '<br><br><p class = "teacherInteractionBoxTitle"></p>';
                                    }
                            echo'</section>';
                                
                            ?>
                        <?php endwhile;
                        }
                        //If the system finds there are no found accounts then the message below is displayed
                        else{
                            echo'<section>';
                                echo'<p>The account you searched for does not exist</p>';
                            echo'</section>';
                        }
                    }
                    //If the system finds there are no accounts with the same type as the user specififed then the system displays the message below
                    else{
                        echo '<section>';
                            echo '<p>The account type you selected does not exist</p>';
                        echo'</section>';
                    }
                    ?>
                   
            </section>
        </section>
    </main>

</html>