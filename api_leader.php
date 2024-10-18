<?php
// print_r($_POST);
require "conn.php";
session_start();

// Check if the user is an admin
if ($_SESSION['usertype'] != 'ADMIN') {
    session_destroy();
    header("location: login.php");
    exit();
}

if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {

    if (isset($_GET['type']) && $_GET['type'] == "add") {
        $id = mysqli_escape_string($conn, $_POST['id']);
        $leader_name = mysqli_escape_string($conn, $_POST['name']);
        $church_role = mysqli_escape_string($conn, $_POST['church_role']);
        $church = mysqli_escape_string($conn, $_POST['church']);
        $designation = mysqli_escape_string($conn, $_POST['designation']);
        $password = mysqli_escape_string($conn, $_POST['password']);
        
        // Get admin_id from session
        $admin_id = $_SESSION['admin_id'];

        // Include admin_id in the INSERT query
        $sql = "INSERT INTO `leaders`(`name`, `church_role`, `designation`, `church`, `password`, `admin_id`) 
                VALUES ('$leader_name','$church_role','$designation','$church','$password', '$admin_id')";
        
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
                Leader Added.
            </div>';
            header("location: leader_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                Something went wrong!.
            </div>';
            header("location: leader_details.php");
            exit();
        }
    }

    if (isset($_GET['type']) && $_GET['type'] == "delete") {
        $id = mysqli_escape_string($conn, $_GET['id']);

        $sql = "DELETE FROM `leaders` WHERE id='$id'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
                Leader Deleted.
            </div>';
            header("location: leader_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                Something went wrong!.
            </div>';
            header("location: leader_details.php");
            exit();
        }
    }

    if (isset($_GET['type']) && $_GET['type'] == "update") {
        $id = mysqli_escape_string($conn, $_POST['id']);
        $leadername = mysqli_escape_string($conn, $_POST['leadername']);
        $church_role = mysqli_escape_string($conn, $_POST['church_role']);
        $church = mysqli_escape_string($conn, $_POST['church']);
        $designation = mysqli_escape_string($conn, $_POST['designation']);
        $password = mysqli_escape_string($conn, $_POST['password']);

        $sql = "UPDATE `leaders` SET `name`='$leadername', `church_role`='$church_role', `designation`='$designation', `church`='$church', `password`='$password' WHERE `id`='$id'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
                Leader Details Updated.
            </div>';
            header("location: leader_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                Something went wrong!.
            </div>';
            header("location: leader_details.php");
            exit();
        }
    }
} else {
    header("location: login.php");
    exit();
}
