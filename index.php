<!DOCTYPE html>
<html>

<head> 
    <title>login</title>
    <link rel="stylesheet" href="mystyle.css">
    <script src="pageInteraction.js"></script>
</head>
<body>
    <section class="centerPos">
        <section id = "loginBox" class="login">
            <form action="Validation.php" method="post">
                <section class = "loginCenter">
                    <h2>Login Here</h2>
                    <label>Username</label><br>
                    <input type="text" name="user" required >
                    <br>
                    <label>Password</label><br>
                    <input type="password" name="password" required>
                </section>

                <section class="loginleft">
                    <button type = "button" class="loginHelpButton" id= "btnHelp" onclick="helpNote('helpSection')">?</button> 
                </section>
        
                <section id = "loginFailMessage" class="hidePost">
                    <p>You failed to input a existing username or password</p>
                </section>

                <section id = "helpSection" class="hidePost loginCenter">
                    <p>Input your username into the section below the title "username" then enter your password into the section below the title "password" then press the button titles "login"</p>
                </section>

                <section class = "loginCenter">
                    <button type="submit" class="loginButton">Login</button>
                </section>
            </form>
        </section>
    </section>

</body>

</html>