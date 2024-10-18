<?php
require "conn.php";
session_start();

date_default_timezone_set('Asia/Manila');

if ($_SESSION['usertype'] != 'ADMIN') {
    session_destroy();
    header("location: login.php");
    exit();
}


if (isset($_POST['year_level']) && isset($_POST['semester']) && isset($_POST['church'])) {
    $year_level = mysqli_escape_string($conn, $_POST['year_level']);
    $semester = mysqli_escape_string($conn, $_POST['semester']);
    $church = mysqli_escape_string($conn, $_POST['church']);
    $ip_address = mysqli_escape_string($conn, $_POST['clientIp']);
    $event_code = mysqli_escape_string($conn, $_POST['event_code']);
    $slot = mysqli_escape_string($conn, $_POST['slot']);
    $date = mysqli_escape_string($conn, $_POST['date']);
    $time = date('h:i:s a', strtotime(mysqli_escape_string($conn, $_POST['time'])));
    $students = $_POST['students'];
    $currentDay = date('l', strtotime($date));

    $success = array();
    $failed = array();

    foreach ($students as $key => $value) {
        $sql = "SELECT * FROM `scholars` WHERE `year_level`='$year_level' AND `student_no`=$value AND `semester`='$semester'";
        $result = mysqli_query($conn, $sql);

        if ($result->num_rows == 1) {
            $csql = "SELECT * FROM `attendance` WHERE `date`='$date' AND `student_no`=$value AND `event_code`=$event_code AND `slot`=$slot AND `year_level`='$year_level'";
            $cres = mysqli_query($conn, $csql);
            if ($cres->num_rows == 0) {
                $fsql = "INSERT INTO `attendance`(`student_no`, `date`, `day`, `event_code`, `slot`, `year_level`, `church`, `semester`, `time`, `ip_address`) VALUES ('$value','$date','$currentDay', '$event_code','$slot','$year_level','$church','$semester','$time','$ip_address')";
                $fres = mysqli_query($conn, $fsql);
                if ($fres) {
                    $success[$value] = "Attendance Marked!.";
                } else {
                    $failed[$value] = " Something went wrong!.";
                }
            } else {
                $failed[$value] = " Attendance Already Marked!.";
            }
        } else {
            $failed[$value] = "This Year Level & Semester Not Allocated to you!.";
        }
    }

    $msg = '
<h5>Success Attendance</h5>
<ol>';

    foreach ($success as $key => $value) {
        $msg .= '<li>Enroll : ' . $key . '</li>';
    }

    $msg .= '</ol>
<h5>Failed Attendance</h5>
<ol>';

    foreach ($failed as $key => $value) {
        $msg .= '<li>Enroll : ' . $key . ', Reason: ' . $value . '</li>';
    }

    $msg .= '</ol>';

    $_SESSION['msg'] = '<div class="alert alert-info text-start mb-2" role="alert">
       ' . $msg . '
        </div>';
    header("location: manual_attend.php");
    exit();
} else {
    $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
       Please ensure to provide the correct details.
        </div>';
    header("location: manual_attend.php");
    exit();
}