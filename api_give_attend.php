<?php

require "conn.php";
session_start();

date_default_timezone_set('Asia/Manila');

if ($_SESSION['usertype'] != 'SCHOLAR') {
    session_destroy();
    header("location: login.php");
    exit();
}

function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
{
    // Convert from degrees to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    return $angle * $earthRadius;
}

$settingsql = "SELECT * FROM `settings` WHERE `id`='1'";
$settingresult = mysqli_query($conn, $settingsql);
$settingrow = mysqli_fetch_assoc($settingresult);

// Example coordinates of the church
$validLatitude = $settingrow['lat'];
$validLongitude = $settingrow['lon'];
$allowedDistance = $settingrow['coverage'];

if (isset($_GET['data']) && isset($_GET['lat']) && isset($_GET['lon'])) {
    $data = mysqli_escape_string($conn, $_GET['data']);
    $lat = mysqli_escape_string($conn, $_GET['lat']);
    $lon = mysqli_escape_string($conn, $_GET['lon']);
    $ip_address = mysqli_escape_string($conn, $_GET['ip_address']);
    $decryptQR = base64_decode($data);
    $arrQRData = json_decode($decryptQR);
    $student_no = mysqli_escape_string($conn, $_SESSION['student_no']);

    $event_code = $arrQRData->event_code;
    $day = $arrQRData->day;
    $slot = $arrQRData->slot;
    $year_level = $arrQRData->year_level;
    $semester = $arrQRData->semester;
    $church = $arrQRData->church;

    $qrTimestamp  = $arrQRData->qrgentime;

    $currentDate = date('Y-m-d');
    $currentDay = date('l');
    $currentTime = date('h:i:s a', time());

    // Check if the QR code is within 1 hour of generation
    $currentTimestamp = time();
    if (($currentTimestamp - $qrTimestamp) > 3600) {
        $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        QR code has expired!.</div>';
        header("location: give_attend.php");
        exit();
    }

    // Check geolocation
    $distance = haversineGreatCircleDistance($validLatitude, $validLongitude, $lat, $lon);
    if ($distance > $allowedDistance) {
        $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        You are not within the allowable distance to mark attendance.</div>';
        header("location: give_attend.php");
        exit();
    }

    // The URL of the API
    $apiUrl = 'http://ip-api.com/json/' . $ip_address . '?fields=status,message,country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,proxy,query';

    // Fetch the contents from the URL
    $response = file_get_contents($apiUrl);

    // Check if the response is not false
    if ($response !== false) {
        // Decode the JSON response
        $ipdata = json_decode($response, true);

        // Check if decoding was successful
        if (json_last_error() === JSON_ERROR_NONE) {
            if ($ipdata['proxy'] != true) {
                // Check if the IP address is from the Philippines
                if ($ipdata['countryCode'] === "PH") {
                    $sql = "SELECT * FROM `scholars` WHERE `year_level`='$year_level' AND `student_no`='$student_no' AND `semester`='$semester' AND `church`='$church'";
                    $result = mysqli_query($conn, $sql);

                    if ($result->num_rows == 1) {
                        // Check if the same IP has marked attendance today
                        $ipAttendanceCheckSql = "SELECT * FROM `attendance` WHERE `date`='$currentDate' AND `ip_address`='$ip_address'";
                        $ipAttendanceResult = mysqli_query($conn, $ipAttendanceCheckSql);

                        if ($ipAttendanceResult->num_rows == 0) {
                            $csql = "SELECT * FROM `attendance` WHERE `date`='$currentDate' AND `student_no`='$student_no' AND `event_code`='$event_code' AND `slot`='$slot' AND `year_level`='$year_level'";
                            $cres = mysqli_query($conn, $csql);
                            if ($cres->num_rows == 0) {
                                $fsql = "INSERT INTO `attendance`(`student_no`, `date`, `day`, `event_code`, `slot`, `year_level`, `church`, `semester`, `time`, `ip_address`) VALUES ('$student_no', '$currentDate', '$currentDay', '$event_code', '$slot', '$year_level', '$church', '$semester', '$currentTime', '$ip_address')";
                                $fres = mysqli_query($conn, $fsql);
                                if ($fres) {
                                    // Insert notification
                                    $notiSQl = "INSERT INTO `attendance_noti`(`student_no`, `date`, `day`, `event_code`, `slot`, `year_level`, `church`, `semester`, `time`, `ip_address`) VALUES ('$student_no', '$currentDate', '$currentDay', '$event_code', '$slot', '$year_level', '$church', '$semester', '$currentTime', '$ip_address')";
                                    $notiREs = mysqli_query($conn, $notiSQl);

                                    // Log any errors during notification insert
                                    if (!$notiREs) {
                                        error_log("Notification insert error: " . mysqli_error($conn));
                                    }

                                    $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
                                    Attendance Marked!.</div>';
                                    header("location: give_attend.php");
                                    exit();
                                } else {
                                    $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                                    Something went wrong!.</div>';
                                    header("location: give_attend.php");
                                    exit();
                                }
                            } else {
                                $_SESSION['msg'] = '<div class="alert alert-warning mb-2" role="alert">
                                Attendance Already Marked!.</div>';
                                header("location: give_attend.php");
                                exit();
                            }
                        } else {
                            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                            One IP Address can only give one attendance per day.</div>';
                            header("location: give_attend.php");
                            exit();
                        }
                    } else {
                        $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                        This Church & Year Level & Semester Not Allocated to you!.</div>';
                        header("location: give_attend.php");
                        exit();
                    }
                } else {
                    $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                    Only Philippine IP Allowed.</div>';
                    header("location: give_attend.php");
                    exit();
                }
            } else {
                $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                VPN and Proxy Usage Not Allowed. Please disable VPN and proxy.</div>';
                header("location: give_attend.php");
                exit();
            }
        }
    } else {
        $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
        VPN & Proxy Check Failed.</div>';
        header("location: give_attend.php");
        exit();
    }
} else {
    $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
    QR & Location Not Provided. Refresh the page and try again.</div>';
    header("location: give_attend.php");
    exit();
}
?>
