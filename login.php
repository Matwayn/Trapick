<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    
    // Check if the password matches
    if ($password == "1234") {
        header("Location: main.html");
        exit();
    } else {     
       header("Location: error.php");
        exit();
    }
}
?>
