<?php
    session_start();

    $nameCheck = $_SESSION['username'];

    $con = mysqli_connect('localhost','root','');
    
    mysqli_select_db($con,'demo');
                
    $teacherPick = "select * from admin where admin_username = '$nameCheck' ";

    $resultTeacher = mysqli_query($con,$teacherPick);
    $numStudent = mysqli_num_rows($resultTeacher);

    if($numStudent == 0){
        $_SESSION['username'] = "";
        header('location:index.php');
    }

    if(isset($_POST['searchAccountBtn'])){

        $firstNameInput = htmlspecialchars($_POST['fNameSearch'],ENT_COMPAT);
        $lastNameInput = htmlspecialchars($_POST['lNameSearch'],ENT_COMPAT);
        $accountType = htmlspecialchars($_POST['searchAccountType'],ENT_COMPAT);
        if($accountType == "Student"){
            $accountQuery = "select * from studentdetails where forname = '$firstNameInput' && surname = '$lastNameInput'";
            $accountQueryResult = mysqli_query($con,$accountQuery);
            $numAccountQueryResult = mysqli_num_rows($accountQueryResult);
            $classSearchName = "students";
        }
        elseif($accountType == "Teacher"){
            $accountQuery = "select * from teacherdetails where teacher_forname = '$firstNameInput' && teacher_surname = '$lastNameInput'";
            $accountQueryResult = mysqli_query($con,$accountQuery);
            $numAccountQueryResult = mysqli_num_rows($accountQueryResult);
            $classSearchName = "teachers";
        }
        elseif($accountType == "Admin"){
            $accountQuery = "select * from admin where forename = '$firstNameInput' && surname = '$lastNameInput'";
            $accountQueryResult = mysqli_query($con,$accountQuery);
            $numAccountQueryResult = mysqli_num_rows($accountQueryResult);
            $classSearchName = "admin";
        }
    }
    else{
        $classSearchName = "none";
        header("Refresh:0; MessengerTeacher.php");
    }
?>
<html>
    <head>
        <title>Messages Histroy result</title>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="mystyle.css">
        <style>
            <?php include 'mystyle.css'; ?>
        </style>
        <script src="pageInteraction.js"></script>
    </head>
    <main>

    <section id = "accountAddClass" class = "centerPosClass hidePost">
        <section class = "classPosts">
        <p class = "teacherInteractionBoxTitle">Update account</p>
            <form action="accountModify.php" method="post">
                <input type="text" name ="accountAddClassID" class = "hidePost"><br>
                <input type="text" name ="accountAddType" class = "hidePost"><br>
                <select name = "lessonSelect">
                    <?php
                    $classListExtract = "select * from classdetails";
                    $classListExtractExecute = mysqli_query($con,$classListExtract);
                    while($rowClassExtract = $classListExtractExecute->fetch_assoc()): ?>
                        <?php
                            $classExtractID = $rowClassExtract['class_id'];
                            $classExtractTitle = $rowClassExtract['class_title'];
                            echo'<option value = "'.$classExtractID.'">'.$classExtractTitle.'</option>';
                        ?>
                    <?php endwhile; 
                    ?>
                </select>
                <b><p>Are you sure you want to add this lesson?</p></b>
                <button name="btnAddClassAccount" class = "button buttonGreen">Yes</button>
                <button onclick="closeDeleteAccountSend()" class = "button buttonRed">No</button>
            </form>
        </section>
    </section>

    <section id = "removeclass" class = "centerPosClass ">
            <section class = "classPosts">
            <p class = "teacherInteractionBoxTitle">Remove lesson from account</p>
                <form action="accountModify.php" method="post">
                    <input type="text" name ="accountUpdateID" class = ""><br>
                    <label class = "displayInline"><b>Class Title: </b></label><input type="text" name="classRemoveTitle" class = "inputButton" required><br>
                    <b><p>Are you sure you want to remove this lesson</p></b>
                    <button name="btnRemoveClass" class = "button buttonGreen">Yes</button>
                    <button onclick="closeDeleteAccountSend()" class = "button buttonRed">No</button>
                </form>
            </section>
        </section>

    <section id = "accountUpdate" class = "centerPosClass hidePost">
            <section class = "classPosts">
            <p class = "teacherInteractionBoxTitle">Update account</p>
                <form action="accountModify.php" method="post">
                    <input type="text" name ="accountUpdateID" class = "hidePost"><br>
                    <input type="text" name ="accountUpdateType" class = "hidePost"><br>
                    <label class = "displayInline"><b>First Name: </b></label><input type="text" name="Fname" class = "inputButton" required><br>
                    <label class = "displayInline"><b>Last Name: </b></label><input type="text" name="Lname" class = "inputButton" required><br>
                    <label class = "displayInline"><b>User Name: </b></label><input type="text" name="Username" class = "inputButton" required><br>
                    <label class = "displayInline"><b>Password: </b></label><input type="text" name="Password" class = "inputButton" required><br>
                    <b><p>Are you sure you want to update this account?</p></b>
                    <button name="btnUpdateAccount" class = "button buttonGreen">Yes</button>
                    <button onclick="closeDeleteAccountSend()" class = "button buttonRed">No</button>
                </form>
            </section>
        </section>

        <section id = "accountDelete" class = "centerPosClass hidePost">
            <section class = "classPosts">
            <p class = "teacherInteractionBoxTitle">Delete account</p>
                <form action="accountModify.php" method="post">
                    <input type="text" name ="accountDeleteID" class = "hidePost"><br>
                    <input type="text" name ="accountDeleteType" class = "hidePost"><br>
                    <label class = "displayInline"><b>First Name: </b></label><p id = "deleteOutputFName" class = "displayInline"></p><br>
                    <label class = "displayInline"><b>Last Name: </b></label><p id = "deleteOutputLName" class = "displayInline"></p><br>
                    <label class = "displayInline"><b>User Name: </b></label><p id = "deleteOutputUserName" class = "displayInline"></p><br>
                    <label class = "displayInline"><b>Password: </b></label><p id = "deleteOutputPassword" class = "displayInline"></p><br>
                    <label class = "displayInline"><b>Account Type: </b></label><p id = "deleteOutputType" class = "displayInline"></p><br>
                    <b><p>Are you sure you want to Remove this account?</p></b>
                    <button name="btnDeleteAccount" class = "button buttonGreen">Yes</button>
                    <button  onclick="closeDeleteAccountSend()" class = "button buttonRed">No</button>
                </form>
            </section>
        </section>


        <section id = "result" class = "centerPosClass">
            <section class = "classPosts">
            <p class = "teacherInteractionBoxTitle">Account details of <?php echo $firstNameInput ?> <?php echo $lastNameInput?></p>
            <form action="administration.php" method="post">
                <br><br><button class = "button expandButton">Return</button>
            </form>
                <?php
                        while($rowClass = $accountQueryResult->fetch_assoc()): ?> 
                            <?php
                                if($classSearchName == "students"){
                                    $selectedID = $rowClass['student_id'];
                                    $studentQuery = "select * from studentdetails where student_id = $selectedID";
                                    $studentQueryExecute = mysqli_query($con,$studentQuery);
                                    $studentDetails = $studentQueryExecute->fetch_assoc();
                                    $selectedUsername = $studentDetails['student_username'];
                                    $selectedPassword = $studentDetails['student_password'];
                                }
                                elseif($classSearchName == "teachers"){
                                    $selectedID = $rowClass['teacher_id'];
                                    $studentQuery = "select * from teacherdetails where teacher_id = $selectedID";
                                    $studentQueryExecute = mysqli_query($con,$studentQuery);
                                    $studentDetails = $studentQueryExecute->fetch_assoc();
                                    $selectedUsername = $studentDetails['teacher_username'];
                                    $selectedPassword = $studentDetails['teacher_password'];
                                }
                                else{
                                    $selectedID = $rowClass['admin_ID'];
                                    $studentQuery = "select * from admin where admin_ID = $selectedID";
                                    $studentQueryExecute = mysqli_query($con,$studentQuery);
                                    $studentDetails = $studentQueryExecute->fetch_assoc();
                                    $selectedUsername = $studentDetails['admin_username'];
                                    $selectedPassword = $studentDetails['admin_password'];
                                }
                                    $accountIDPick = rand();
                                    $accountFName = rand();
                                    $accountLName = rand();
                                    $accountUsername = rand();
                                    $accountPassword = rand();
                                    $accountTypePick = rand();
                                    echo'<br><section>';
                                        echo '<p id = "'.$accountIDPick.'" class = "hidePost">'.$selectedID.'</p>';
                                        echo'<label class = "displayInline">First name: </label><p id = "'.$accountFName.'" class = "displayInline">'.$firstNameInput.'</p><br>';
                                        echo'<label class = "displayInline">Last name: </label><p id = "'.$accountLName.'" class = "displayInline">'.$lastNameInput.'</p><br>';
                                        echo'<label class = "displayInline">Username: </label><p id = "'.$accountUsername.'" class = "displayInline">'.$selectedUsername.'</p><br>';
                                        echo'<label class = "displayInline">Password: </label><p id = "'.$accountPassword.'" class = "displayInline">'.$selectedPassword.'</p><br>';
                                        echo'<label class = "displayInline">Account Type: </label><p id = "'.$accountTypePick.'"  class = "displayInline">'.$accountType.'</p><br>';
                                        echo '<button onclick = "deleteAccountSend('.$accountIDPick.','.$accountTypePick.','.$accountFName.','.$accountLName.','.$accountUsername.','.$accountPassword.')" class="button">Delete Account</button>';
                                        echo '<button onclick ="updateAccountSend('.$accountIDPick.','.$accountTypePick.','.$accountFName.','.$accountLName.','.$accountUsername.','.$accountPassword.')" class="button">Update Account</button>';
                                        echo '<button onclick ="addAccountSend('.$accountIDPick.','.$accountTypePick.')" class="button">Add Classes to account</button>';
                                    echo'</section>';

                                

                                echo'<br><section class = "">';
                                    echo'<p class = "teacherInteractionBoxTitle">Assigned classes</p>';
                                    $classSelect = "select class_id from studentdetails_classdetails where student_id = $selectedID";
                                    $classQuery = mysqli_query($con,$classSelect);
                                    $numClassQueryResult = mysqli_num_rows($classQuery);
                                    while($classRow = $classQuery->fetch_assoc()): ?> 
                                        <?php
                                            $classID = $classRow['class_id'];
                                            $classExtract = "select * from classdetails where class_id = $classID";
                                            $classExtractExecution = mysqli_query($con,$classExtract);
                                            $numClassExtractExecution = mysqli_num_rows($classExtractExecution);
                                            $classExtractRow = $classExtractExecution->fetch_assoc();
                                            $className = $classExtractRow['class_title'];
                                            if($numClassExtractExecution >= 1){
                                                echo '<br><section class = "classOutliner">';
                                                    echo '<input type="text" name ="" value = "'.$classID.'" class = ""><br>';
                                                    echo '<label class = "displayInline"><b>Class Name: </b></label><p class = "displayInline">'.$className.'</p><br>';
                                                    echo '<button onclick ="" class="button">Remove</button>';
                                                    echo '<button onclick ="" class="button">Update</button>';

                                                echo'</section>';
                                            }
                                            else{
                                                echo '<section class = "classOutliner">';
                                                    echo '<p>This account has not been assigned any classes</p>';
                                                echo'</section>';
                                            }
                                        ?>
                                    <?php endwhile;
                                echo'</section>';
                            ?>
                        <?php endwhile;

                ?>
            <?php

            ?>
            </section>
        </section>
    </main>

</html>