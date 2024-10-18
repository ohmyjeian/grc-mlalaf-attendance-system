<?php
require "conn.php";
session_start();

if (isset($_SESSION['logged']) && $_SESSION['logged'] == true && $_SESSION['usertype'] == 'SCHOLAR') {

    $student_no = $_SESSION['student_no'];
    $student_name = mysqli_escape_string($conn, $_POST['student_name']);
    $filename = $_FILES['pic']['name'];

    // Use absolute path for upload directory
    $uploadDir = 'C:/xampp/htdocs/grc-mlalaf-attendance-system/img/profile/';

    // Check if the upload directory exists and is writable
    if (!is_dir($uploadDir)) {
        die('Upload directory does not exist: ' . realpath($uploadDir));
    }
    if (!is_writable($uploadDir)) {
        die('Upload directory is not writable: ' . realpath($uploadDir));
    }

    if ($_FILES['pic']['error'] != UPLOAD_ERR_NO_FILE) {

        // File upload
        // Check file extension
        $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($fileExtension, ['jpg', 'png', 'jpeg'])) {
            die('Invalid file type. Please upload jpg, png, or jpeg.');
        }

        // Create a new filename with a timestamp
        $filename = time() . "." . $fileExtension;
        $uploadFile = $uploadDir . $filename;

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES['pic']['tmp_name'], $uploadFile)) {
            // Update the database with the new filename
            $sql = "UPDATE `scholars` SET `name`='$student_name', `pic`='$filename' WHERE `student_no`='$student_no'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                // Set the session variable for profile_pic
                $_SESSION['profile_pic'] = $filename; // Store the filename in the session

                $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
                    Student Details Updated.
                </div>';
                header("location: profile_stud.php");
                exit();
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                    Something went wrong!.
                </div>';
                header("location: profile_stud.php");
                exit();
            }
        } else {
            die('File upload failed.');
        }
    } else {
        // Update name only if no file is uploaded
        $sql = "UPDATE `scholars` SET `name`='$student_name' WHERE `student_no`='$student_no'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
                Scholar Details Updated.
            </div>';
            header("location: profile_stud.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                Something went wrong!.
            </div>';
            header("location: profile_stud.php");
            exit();
        }
    }
} else {
    header("location: login.php");
    exit();
}
