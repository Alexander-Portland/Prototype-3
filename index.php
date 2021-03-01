<!DOCTYPE html>
<html>

<head> 
    <title>login</title>
    <link rel="stylesheet" href="mystyle.css">
    <script>

        function helpNote(helpSection) {
            var hideVar = document.getElementById(helpSection);
            if (hideVar.style.display === "none") {
                hideVar.style.display = "block";
                document.getElementById("loginBox").style.height = "16em";
            } else {
                hideVar.style.display = "none";
                document.getElementById("loginBox").style.height = "10em";
            }
        }
        function failedLoginNote() {
            var hideVar = document.getElementById('loginFailMessage');
            if (hideVar.style.display === "none") {
                hideVar.style.display = "block";
            } else {
                hideVar.style.display = "none";
            }
        }
    </script>
</head>
<body class="background">
    <section class = "background"></section>
    <section class="centerPos">
    <section id = "loginBox" class="login">
        <section class = "loginCenter">
            <h2>Login Here</h2>
            <form action="Validation.php" method="post">
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