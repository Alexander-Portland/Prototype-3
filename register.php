<?php

session_start();
header('location:index.php');

$con = mysqli_connect('localhost','root','');

mysqli_select_db($con,'demo');

$name = $_POST['user'];
$pass = $_POST['password'];

$s = "select * from studentdetails where student_username = '$name'";
$result = mysqli_query($con,$s);
$num = mysqli_num_rows($result);
if($num == 1){
    echo "username already taken";
}
else{
    $reg = "insert into studentdetails(student_username,student_password) value ('$name','$pass')";
    mysqli_query($con,$reg);
    echo "Registration Successful";
}
?>