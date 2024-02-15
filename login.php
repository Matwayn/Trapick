<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    
    // Check if the password matches
    if ($password == "trapick") {
        header("Location: main.html");
        exit();
    } else {     
       header("Location: error.php");
        exit();
    }
}
