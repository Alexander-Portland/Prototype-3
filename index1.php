<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT student_id, student_username, student_password FROM studentdetails";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "id: " . $row["student_id"]. " " . $row["student_username"]. " " . $row["student_password"]. "<br>";
  }
} else {
  echo "0 results";
}             
$conn->close();
?>



<!DOCTYPE html>
<html>
<head>
	<title> Login Form in HTML5 and CSS3</title>
	<link rel="stylesheet" a href="style.css">
</head>

<script>
	function myFunction(){
		if(document.getElementById('user').value == ""){
			alert('please enter a username');
			return(false)
		}
		if(document.getElementById('pass').value == ""){
			alert('please enter a password');
			return(false)
		}
		else{
			alert('success');
		}
		
	}
</script>

<body>
	<p>Username:</p><input type="text" id="user"><br>
	<p>Password:</p><input type="text" id="pass"><br>
	<button onclick="myFunction()">Login</button>
</body>
</html>