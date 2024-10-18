<?php
require "conn.php";
session_start();

// Check if user is an admin
if ($_SESSION['usertype'] != 'ADMIN') {
    session_destroy();
    header("location: login.php");
    exit();
}

if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {

    if (isset($_GET['type']) && $_GET['type'] == "add") {
        $eventcode = mysqli_escape_string($conn, $_POST['event_code']);
        $description = mysqli_escape_string($conn, $_POST['name']);
        $semester = mysqli_escape_string($conn, $_POST['semester']);
        
        // Get admin_id from the session
        $admin_id = $_SESSION['admin_id'];

        // Include admin_id in the INSERT query
        $sql = "INSERT INTO `events`(`event_code`, `name`, `semester`, `admin_id`) 
                VALUES ('$eventcode','$description','$semester','$admin_id')";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
                Event Added.
            </div>';
            header("location: event_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                Something went wrong!
            </div>';
            header("location: event_details.php");
            exit();
        }
    }

    if (isset($_GET['type']) && $_GET['type'] == "delete") {
        $event_code = mysqli_escape_string($conn, $_GET['event_code']);    

        $sql = "DELETE FROM `events` WHERE event_code='$event_code'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
                Event Deleted.
            </div>';
            header("location: event_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                Something went wrong!
            </div>';
            header("location: event_details.php");
            exit();
        }
    }

    if (isset($_GET['type']) && $_GET['type'] == "update") {
        $eventcode = mysqli_escape_string($conn, $_POST['event_code']);
        $description = mysqli_escape_string($conn, $_POST['description']);
        $semester = mysqli_escape_string($conn, $_POST['semester']);
        $admin_id = $_SESSION['admin_id'];


        $sql = "UPDATE `events` SET `name`='$description', `semester`='$semester', `admin_id`='$admin_id' 
                WHERE `event_code`='$eventcode'";
       
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
                Event Details Updated.
            </div>';
            header("location: event_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                Something went wrong!
            </div>';
            header("location: event_details.php");
            exit();
        }
    }
} else {
    header("location: login.php");
    exit();
}
