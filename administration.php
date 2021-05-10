<?php

session_start();

//the session username and password are extracted for account checks
$nameCheck = $_SESSION['username'];
$passCheck = $_SESSION['password'];

//These session details are used as identification for the viewAccounts.php page
$_SESSION['passName'] = $nameCheck;
$_SESSION['passPassword'] = $nameCheck;

$con = mysqli_connect('localhost','root','');

mysqli_select_db($con,'demo');

//this query checks if the session username and password match and existing admin account 
$teacherPick = "select admin_ID, forename, surname from admin where admin_username = '$nameCheck' && admin_password = '$passCheck' ";


$resultStudent = mysqli_query($con,$teacherPick);
$numStudent = mysqli_num_rows($resultStudent);

//The account ID, username and password are extracted
$row = $resultStudent->fetch_assoc();
$ID = $row['admin_ID'];
$Fname = $row['forename'];
$Lname = $row['surname'];

//If the session username or password do not match an existing admin account then the page is redirected to the index page
if($numStudent == 0){
    $_SESSION['username'] = "";
    header('location:index.php');
}
?>
<!--This Admin page gives the user access to view, add , update and delete classes as well as add and modify accounts-->
<html>
    <head>
        <!--The admin page is linked to a single css and javascript page-->
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

    <!--The admin page's navigation bar allows the user access the login sequence -->
    <nav class="NavBar">
        <p class = "navBarTitle">Welcome <?php echo $Fname ?> <?php echo $Lname?></p>
        <a href="logout.php"><b>Log out</b></a>
    </nav>

    <!--When loaded the admin page keeps the help box hidden until the user interacts with the help button-->
    <section id="myhelp" class="help">
        <section class="helpContent">
            <!--The help box can be hidden when the user presses the close button-->
            <span id = "helpClose" class="close">&times;</span>
            <label class = "loginLabel"><b>Using the admin page</b></label>
            <p class = "loginHelpText">To use the admin page, take the following steps</p>

            <p class = "loginHelpText">To view the existing classes press the “View classes” button. This will display a form with each class in its own box. 
                To delete a class press the “delete” button adjacent to the class you wish to delete. This will produce a form asking 
                form final confirmation. Pressing yes will permanently delete the selected class, pressing no will abort the process 
                and close the form. </p>
            
            <p class = "loginHelpText">If you wish to update a class, press the update button adjacent to the class you wish to update. 
                When pressed an update form will be opened with the class title and description loaded into the class title and 
                description textboxes. Make the necessary changes to the title and description and when you are satisfied with the 
                change, press the yes button, to cancel the update simply press no which will close the form.</p>

            <p class = "loginHelpText">To add a class to the system, press the add class button. When pressed the add class form will be 
                displayed. Enter the title of the class in the textbox below the label “class title”, then enter the description of the class 
                in the textbox below the label “class description”. When satisfied with the class title and description press the yes button 
                to submit the class or no to abort and close the form.</p>

            <p class = "loginHelpText">To add a new account, press the “add account” button, this will display a new form. Enter the first and 
                last name, a unique username, the password and finally the account type. Once you are satisfied with the credentials of the new 
                account, press the yes button to submit or no to abort and close the form. Once submitted the account will be active and able to 
                login to the system.</p>

            <p class = "loginHelpText">To assign classes or change the details of an existing account, first press the search button and enter the 
                first and last name of the account, as well as entering the type of account. Once you are satisfied with your search inputs, press 
                the yes button to submit the search, or press no to abort and close the form.</p>

            <p class = "loginHelpText">Once the search is complete, for each account with the same first and last name and account type, the details 
                of the account and the lessons they have been assigned will be displayed in their own separate box.</p>

            <p class = "loginHelpText">If you wish to return to the homepage, then simply press the “return to admin page” button. To delete an account 
                press the delete button inside of the account detail box and press yes on the confirm box, or no to cancel the deletion.</p>

            <p class = "loginHelpText">To update the account details, press the update button inside the account detail box where you will be able to 
                modify the details of the first and last name, username and password. When satisfied with the detail changes, press the yes button or 
                no to cancel the update.</p>

            <p class = "loginHelpText">To add a class to an account, press the “add classes to account” button. When pressed a new form with a class 
                drop down menu will be displayed. Select the class you wish to add. When satisfied with the selected class press the yes button which 
                will submit the class add or press no to abort and close the form.</p>

            <p class = "loginHelpText">To remove an assigned lesson from an account press the remove button on the class you wish to remove. When pressed 
                the remove form will be opened with a yes or no button. Press yes to remove the class assignment or press no to cancel and close the form.</p>

            <p class = "loginHelpText">To update a class assignment from an account press the update button on the class slot you wish to reassign. When 
                pressed this will open a form that will display the title of the old class and a drop down menu to select the new class. Once you have 
                selected the new class, press the yes button to confirm the update or press no to abort and close the form.</p>

            <p class = "loginHelpText">If you wish to log out, go to the admin home page and press the log out button on the navigation bar.</p>

            <!--This is the video segement of the tutorial-->
            <video class = "helpVideo" controls>
                <source src="vid/adminPageHelp.mp4" type="video/mp4">
            </video>
        </section>
    </section>

    <!-- This section acts as thhe secondary navigation bar giving the user access to the different page function-->
    <section class = "centerPosClass">
            <section class = "classPosts">
                <!--The image below will reveal the help box when pressed-->
                <img src="img\helpButton.png" id="helpBtn" alt="Missing help button" class = "helpButton" width = 40x>
                <!--When the below button is pressed the view classes section is displayed-->
                <button onclick="viewClasses()" class= "button">View Classes</button>
                <!--When the below button is pressed the add class section is displayed-->
                <button onclick="addClass()" class= "button">Add Classes</button>
                <!--When the below button is pressed the add account section is displayed-->
                <button onclick="addAccount()" class= "button">Add Account</button>
                <!--When the below button is pressed the search account section is displayed-->
                <button onclick="openSearch()" class= "button">Search</button>
            </section>
    </section>

    <main>

    <!--This section allows the user to add new classes to the system-->
    <section id = "classAdd" class = "centerPosClass hidePost" novalidate>
            <section class = "classPosts">
                    <!--The form below contains the inputs for class title, description and the yes or no buttons that confirm or abort the process -->
                    <form action="administrationSubmission.php" method="post">
                        <p class = "teacherInteractionBoxTitle">Add New Class</p> 
                        <p><b>Class Title: </b></p><input type="text" name="classNameInput" class = "inputButton" required><br>
                        <p><b>Class Description:  </b></p><textarea type="text" name="classDescriptionInput" class = "textInput" required></textarea><br>
                        <p><b>Are you sure you want to send this question?</b></p>
                        <!--When the yes buttton is pressed the class submission is executed-->
                        <button name="sendNewClass" class = "button buttonGreen">Yes</button>
                        <!--When the no button is pressed the class submission is aborted and the section is closed-->
                        <button onclick="closeOnNo()" class = "button buttonRed" type = "button">No</button>  
                    </form> 
            </section>
        </section>
        
        <!--This section allows the user to delete a class they choose-->
        <section id = "deleteClass" class = "centerPosClass hidePost">
                <section class = "classPosts">
                    <p class = "teacherInteractionBoxTitle">Delete Class</p>
                    <!--The form below contains the identification of the class being deleted and its title as well as displauing the class name, description and a yes or no input button--> 
                    <form action="administrationSubmission.php" method="post">
                        <input type="text" name ="classDeleteID" class = "hidepost"><br>
                        <input type="text" name ="classDeleteTitle" class = "hidepost"><br>
                        <p class = "displayInline"><b>Class Name: </b></p><p id = "deleteClassName" class = "displayInline"></p><br> 
                        <p class = "displayInline"><b>Class Description: </b></p><p id = "deleteClassDescription" class = "displayInline"></p><br>
                        <b><p>Are you sure you want to delete this?</p></b>
                        <!--If the user presses the yes button then the deletion process is submitted -->
                        <button name="btnDelete" class = "button buttonGreen">Yes</button>
                        <!--If the user presses the no button then the deletion process is aborted and the section is hidden-->
                        <button onclick="closeOnNo()" class = "button buttonRed" type = "button">No</button>
                    </form>
            </section>
        </section>

        <!--This section allows the user to update the details of an existing page -->
        <section id = "updateClass" class = "centerPosClass hidePost">
            <section class = "classPosts">
                <p class = "teacherInteractionBoxTitle">Update Class</p>
                    <!--The form below contains inputs to change the name and description of the class as well as a yes and no button-->
                    <form action="administrationSubmission.php" method="post">
                        <input type="text" name ="classUpdateID" class = "hidePost"><br>
                        <p>Class Name: </p><input type="text" name="classUpdate" class = "inputButton" required><br>
                        <p>Class Description: </p><textarea type="text" name="classDescriptionUpdate" class = "textInput" required></textarea><br>
                        <b><p>Are you sure you want to update this?</p></b>
                        <!--If the user presses the yes button then the update process is submitted-->
                        <button name="search" class = "button buttonGreen">Yes</button>
                        <!--If the user presses the no button then the update process is aborted and the section is hidden-->
                        <button onclick="closeOnNo()" class = "button buttonRed" type = "button">No</button>
                    </form>
            </section>
        </section>

        <!--This section allows the user to add a new account -->
        <section id = "addAcount" class = "centerPosClass hidePost">
            <section class = "classPosts">
                <p class = "teacherInteractionBoxTitle">Add Account</p>
                    <!--The form below contains inputs for the first name, last name, usernam, password, account type, yes and no button-->
                    <form action="administrationSubmission.php" method="post">
                        <input type="text" name ="classUpdateID" class = "hidePost"><br>
                        <p><b>First Name: </b></p><input type="text" name="accountFirstName" class = "inputButton" required><br>
                        <p><b>Last Name: </b></p><input type="text" name="accountLastName" class = "inputButton" required><br>
                        <p><b>User Name: </b></p><input type="text" name="accountUserName" class = "inputButton" required><br>
                        <p><b>Password: </b></p><input type="text" name="accountPassword" class = "inputButton" required><br>
                        <!--The select box below allows the user to decide what kind of type the new account will be-->
                        <p class = "displayInline"><b>Account type: </b></label><select name = "accountTypeSelect">
                            <option value="Student">Student</option>
                            <option value="Teacher">Teacher</option>
                            <option value="Admin">Admin</option>
                        </select>
                        <b><p>Are you sure you want to update this?</p></b>
                        <!--If the user presses the yes button the add account function is executed -->
                        <button name="accountAdd" class = "button buttonGreen">Yes</button>
                        <!--If the user presses the no button the add account is aborted and the section is hidden-->
                        <button onclick="closeOnNo()" class = "button buttonRed" type = "button">No</button>
                    </form>
            </section>
        </section>

        <!--This section allows the user to search for an existing account -->
        <section id = "seachAccount" class = "centerPosClass hidePost">
            <section class = "classPosts">
                <!--The form below contains five inputs: the first name, last name, account type and a yes or no button -->
                <form action="viewAccounts.php" method="post">
                    <p id = "addClassTitle" class = "teacherInteractionBoxTitle">Search Account</p>
                    <p><b>First Name</b></p><input type="text" name="fNameSearch" class = "inputButton" required>
                    <p><b>Last Name</b></p><input type="text" name="lNameSearch" class = "inputButton" required>
                    <!--The select input allows the user to specify the account type they are searching for-->
                    <p class = "displayInline"><b>Account type: </b></label><select name = "searchAccountType">
                            <option value="Student">Student</option>
                            <option value="Teacher">Teacher</option>
                            <option value="Admin">Admin</option>
                        </select>
                    <p><b>Are you sure you wish to search for this account?</b></p>
                    <!--If the user presses the yes button the search function is executed-->
                    <button name="searchAccountBtn" class = "button buttonGreen">Yes</button>
                    <!--If the user presses the no button the search function is aborted and the section is hidden-->
                    <button onclick="closeOnNo()" class = "button buttonRed" type = "button">No</button> 
                </form>
            </section>
        </section>

        <!--This section displays all of the classes with th ability to add, update and delete classes-->
        <section id = "classViewer" class = "centerPosClass hidePost">
            <section class = "classPosts">
                <p class = "teacherInteractionBoxTitle">View Classes</p>
                <?php
                    //the query below extracts all of the classes and their details
                    $classPick = "select * from classdetails";
                    $resultClass = mysqli_query($con,$classPick);
                    $numClass = mysqli_num_rows($resultClass);

                    //the system checks if there are any existing classes
                    if($numClass >= 1){
                        while($rowClass = $resultClass->fetch_assoc()): ?> 
                            <?php
                                //randomly generated IDs are needed to identify the content of each displayed class
                                $classElementId = rand();
                                $classElementTitle = rand();
                                $classElementDescription = rand();

                                //The class ID, title and description are extracted for display
                                $classID = $rowClass["class_id"];
                                $classTitleID = $rowClass["class_title"];
                                $classElementDescriptionID = $rowClass["description"];

                                //the section below is displayed for each existing class with the class ID, title, description, update and delete button
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
                    //If there are no classes on the system then the message below is displayed
                    else{
                        echo '<p>No classes have been added to the system.</p>';
                    }
                ?>
            </section>
        </section>
        
        <!--This script is used to display and hide the help box -->
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