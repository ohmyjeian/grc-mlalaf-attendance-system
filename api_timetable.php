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
        $academic_year = mysqli_escape_string($conn, $_POST['academicyear']);
        $semester = mysqli_escape_string($conn, $_POST['semester']);
        $year_level = mysqli_escape_string($conn, $_POST['year_level']);
        $church = mysqli_escape_string($conn, $_POST['church']);

        // Get admin_id from the session
        $admin_id = $_SESSION['admin_id'];

        $esql = "SELECT * FROM `timetable` WHERE `academic_year`='$academic_year' AND `church`='$church' AND `semester`='$semester' AND `year_level`='$year_level'";
        $eresult = mysqli_query($conn, $esql);

        if ($eresult->num_rows == 0) {
            $slots = $_POST['slots'];

            $Monday = $_POST['Monday'];
            $Tuesday = $_POST['Tuesday'];
            $Wednesday = $_POST['Wednesday'];
            $Thursday = $_POST['Thursday'];
            $Friday = $_POST['Friday'];
            $Saturday = $_POST['Saturday'];

            foreach ($slots as $key => $value) {
                $slot = $key + 1;

                // Include admin_id in each insert statement
                $sql1 = "INSERT INTO `timetable`(`semester`, `year_level`, `church`, `day`, `slot`, `slotlabel`, `event_code`, `academic_year`, `admin_id`) VALUES ('$semester', '$year_level', '$church', 'Monday', '$slot', '$value', '$Monday[$key]', '$academic_year', '$admin_id')";
                $result1 = mysqli_query($conn, $sql1);

                $sql2 = "INSERT INTO `timetable`(`semester`, `year_level`, `church`, `day`, `slot`, `slotlabel`, `event_code`, `academic_year`, `admin_id`) VALUES ('$semester', '$year_level', '$church', 'Tuesday', '$slot', '$value', '$Tuesday[$key]', '$academic_year', '$admin_id')";
                $result2 = mysqli_query($conn, $sql2);

                $sql3 = "INSERT INTO `timetable`(`semester`, `year_level`, `church`, `day`, `slot`, `slotlabel`, `event_code`, `academic_year`, `admin_id`) VALUES ('$semester', '$year_level', '$church', 'Wednesday', '$slot', '$value', '$Wednesday[$key]', '$academic_year', '$admin_id')";
                $result3 = mysqli_query($conn, $sql3);

                $sql4 = "INSERT INTO `timetable`(`semester`, `year_level`, `church`, `day`, `slot`, `slotlabel`, `event_code`, `academic_year`, `admin_id`) VALUES ('$semester', '$year_level', '$church', 'Thursday', '$slot', '$value', '$Thursday[$key]', '$academic_year', '$admin_id')";
                $result4 = mysqli_query($conn, $sql4);

                $sql5 = "INSERT INTO `timetable`(`semester`, `year_level`, `church`, `day`, `slot`, `slotlabel`, `event_code`, `academic_year`, `admin_id`) VALUES ('$semester', '$year_level', '$church', 'Friday', '$slot', '$value', '$Friday[$key]', '$academic_year', '$admin_id')";
                $result5 = mysqli_query($conn, $sql5);

                $sql6 = "INSERT INTO `timetable`(`semester`, `year_level`, `church`, `day`, `slot`, `slotlabel`, `event_code`, `academic_year`, `admin_id`) VALUES ('$semester', '$year_level', '$church', 'Saturday', '$slot', '$value', '$Saturday[$key]', '$academic_year', '$admin_id')";
                $result6 = mysqli_query($conn, $sql6);
            }

            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
                Time Table Added.
            </div>';
            header("location: timetable.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-warning mb-2" role="alert">
                Time Table Already Exists.
            </div>';
            header("location: timetable.php");
            exit();
        }
    }

    if (isset($_GET['type']) && $_GET['type'] == "delete") {
        $academic_year = mysqli_escape_string($conn, $_POST['academic_year']);
        $semester = mysqli_escape_string($conn, $_POST['semester']);
        $year_level = mysqli_escape_string($conn, $_POST['year_level']);
        $church = mysqli_escape_string($conn, $_POST['church']);

        $esql = "SELECT * FROM `timetable` WHERE `academic_year`='$academic_year' AND `church`='$church' AND `semester`='$semester' AND `year_level`='$year_level'";
        $eresult = mysqli_query($conn, $esql);

        if ($eresult->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($eresult)) {
                $sql = "DELETE FROM `timetable` WHERE id=" . $row['id'];
                $result = mysqli_query($conn, $sql);
            }

            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
                Time Table Deleted.
            </div>';
            header("location: timetable.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-warning mb-2" role="alert">
                Time Table Not Found! 
            </div>';
            header("location: timetable.php");
            exit();
        }
    }
} else {
    header("location: login.php");
    exit();
}
