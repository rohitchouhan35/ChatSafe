<?php
session_start();
include_once "config.php";
$fname = mysqli_real_escape_string($conn, $_POST['fname']);
$lname = mysqli_real_escape_string($conn, $_POST['lname']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

if (!empty($fname) && !empty($lname) && !empty($email) && !empty($password)) {
    // Check if the email is valid
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // echo "Valid email.";
        
        // Check if the email is already registered
        $sql = mysqli_query($conn, "SELECT email FROM users WHERE email = '{$email}'");
        if (mysqli_num_rows($sql) > 0) {
            echo "$email - already exists.";
        } else {
            // Check if a file is uploaded
            if (isset($_FILES['image'])) {
                $_img_name = $_FILES['image']['name']; // getting the user-uploaded img name
                $img_type = $_FILES['image']['type'];
                $tmp_name = $_FILES['image']['tmp_name']; // this temporary name is used to save/move the file to our folder

                // Let's explode the image and get the last expression like jpg/png
                $img_explode = explode('.', $_img_name);
                $img_ext = end($img_explode); // here we get the extension of the user-uploaded img file

                $extensions = ['png', 'jpeg', 'jpg']; // valid extensions

                if (in_array($img_ext, $extensions) === true) { // if the user-uploaded img extension is matched with any valid extensions
                    $time = time(); // current time, needed so that while uploading the image to the folder, we rename the user file with the current time, ensuring all image files will have a unique name

                    // Let's move the user-uploaded image to our specified folder
                    $new_img_name = $time . $_img_name;
                    if (move_uploaded_file($tmp_name, "../images/" . $new_img_name)) { // if successfully moved
                        $status = "Active now";
                        $random_id = rand(time(), 10000000); // creating a random id for the user

                        // Let's insert all user data into the database table
                        $encrypt_pass = md5($password);
                        $sql2 = mysqli_query($conn, "INSERT INTO users (unique_id, fname, lname, email, password, img, status)
                            VALUES ({$random_id}, '{$fname}', '{$lname}', '{$email}', '{$encrypt_pass}', '{$new_img_name}', '{$status}')");

                        if ($sql2) { // if data is inserted
                            $sql3 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                            if (mysqli_num_rows($sql3) > 0) {
                                $row = mysqli_fetch_assoc($sql3);
                                $_SESSION['unique_id'] = $row['unique_id']; // using this session, we can access the user's unique_id in other PHP files
                                echo "success";
                            } else {
                                echo "Something went wrong!";
                            }
                        }
                    }
                } else {
                    echo "Invalid file format!";
                }
            } else {
                echo "Please select an image file!";
            }
        }
    } else {
        echo "$email is not a valid email!";
    }
} else {
    echo "All input fields are required.";
}
?>
