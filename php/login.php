<?php
session_start();
include_once "config.php";
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

if (!empty($email) && !empty($password)) {
    // Check if email and password exist in the database in the same row
    $un_hash_password = md5($password);
    $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}' AND password = '{$un_hash_password}'");
    
    if (mysqli_num_rows($sql) > 0) { // If user's credentials match
        $row = mysqli_fetch_assoc($sql);
        $status = "Active now";
        // updating user status to active now if user login successsfully
        $sql2 = mysqli_query($conn, "UPDATE users SET status = '{$status}' WHERE unique_id = {$row['unique_id']}");
        if($sql) {
            $_SESSION['unique_id'] = $row['unique_id']; // Using this session, we can access the user's unique_id in other PHP files
            echo "success";
        }
    } else {
        echo "Email or Password is incorrect!";
    }
} else {
    echo "All input fields are required!";
}
?>
