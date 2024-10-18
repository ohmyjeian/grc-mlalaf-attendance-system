<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require "conn.php";

date_default_timezone_set('Asia/Manila');

// Check if the user is logged in as a leader
if ($_SESSION['usertype'] != 'LEADER') {
    session_destroy();
    header("location: login.php");
    exit();
}

// Sanitize input parameters
$church = mysqli_escape_string($conn, $_GET['church']);
$year_level = mysqli_escape_string($conn, $_GET['year_level']);
$semester = mysqli_escape_string($conn, $_GET['semester']);
$slot = mysqli_escape_string($conn, $_GET['slot']);

// Query to fetch unread notifications
$sql = "SELECT * FROM attendance_noti WHERE is_read = 0 AND `church`='$church' AND `year_level`='$year_level' AND `semester`='$semester' AND `slot`='$slot' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

$notifications = array();
if ($result) {
    // Fetch notifications
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $nsql = "SELECT `name` FROM `scholars` WHERE `student_no`='".$row['student_no']."'";
            $nres = mysqli_query($conn, $nsql);
            if ($nres) {
                $nrow = mysqli_fetch_assoc($nres);
                $row['name'] = $nrow['name'];
            } else {
                error_log("Error fetching scholar name: " . mysqli_error($conn));
                $row['name'] = "Unknown"; // Default name if there's an error
            }
            $notifications[] = $row;
        }

        // Delete fetched notifications
        if (!empty($notifications)) {
            $ids = array_map(function ($notification) {
                return $notification['id'];
            }, $notifications);
            $ids_str = implode(',', $ids);

            $delete_sql = "DELETE FROM attendance_noti WHERE id IN ($ids_str)";
            if ($conn->query($delete_sql) !== TRUE) {
                error_log("Error deleting records: " . $conn->error);
                echo json_encode(array("status" => "error", "message" => "Error deleting records."));
                exit();
            }
        }
    }
    echo json_encode($notifications);
} else {
    // If the query fails, log the error and return an empty array
    error_log("Error fetching notifications: " . mysqli_error($conn));
    echo json_encode(array("status" => "error", "message" => "Error fetching notifications."));
}

$conn->close();
