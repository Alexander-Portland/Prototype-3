<!DOCTYPE html>
<html>

<head> 
    <title>login</title>
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="mystyle.css">
    <script src="pageInteraction.js"></script>
</head>

<main>
    <section id="myhelp" class="help">
        <section class="helpContent">
            <span class="close">&times;</span>
            <label class = "loginLabel"><b>Using the login page</b></label>
            <p class = "loginHelpText">To log in, take the following steps</p>
            <ol>
            
                <li>Type you're username in the text box below "Username"</li>
                <li>Type you're password in the text box below "password"</li>
                <li>Press the login button</li>

            </ol>
            <video class = "helpVideo" controls>
                <source src="vid/loginHelp.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </section>
    </section>

    <section class="loginBoxPos">
        <section id = "loginBox" class="loginBoxSettings">
            <form action="Validation.php" method="post">
                <section class = "">
                    <h2 class ="loginTitle">Welcome Back!</h2>
                    <label class = "loginLabel">Username</label><br>
                    <img src="img\user.png" id="" alt="Missing user picture" class = "helpButton" width = 40px>
                    <input type="text" name="user" class = "loginInputButton" required >
                    <br>
                    <label class = "loginLabel">Password</label><br>
                    <img src="img\lock.png" id="" alt="Missing lock picture" class = "helpButton" width = 40px>
                    <input type="password" name="password" class = "loginInputButton" required>
                </section>
                <img src="img\helpButton.png" id="helpBtn" alt="Missing help button" class = "helpButton" width = 40x>
                <section class = "loginCenter">
                    <button type="submit" class="loginInputButton button">Login</button>
                </section>

            </form>
    </section>
    <script>
        var selectHelp = document.getElementById("myhelp");
        var btn = document.getElementById("helpBtn");
        var span = document.getElementsByClassName("close")[0];

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