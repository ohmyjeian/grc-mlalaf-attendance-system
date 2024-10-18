<?php
require('header.php');
require('conn.php');

if (isset($_GET['event_code']) && isset($_GET['slot']) && isset($_GET['year_level']) && isset($_GET['semester'])) { 
    $event_code = $_GET['event_code']; 
    $slot = $_GET['slot'];
    $year_level = $_GET['year_level']; 
    // $currentDay = $_GET['day'];
    $semester = $_GET['semester'];
    $church = $_GET['church']; 

    $currentDate = date('d-m-Y');
    $currentDay = $_GET['day'];
    $currentTime = date('h:i:s a', time());

    $fssql = "SELECT * FROM `events` WHERE `event_code`=" . $event_code; 
    $fsresult = mysqli_query($conn, $fssql);
    $fsrow = mysqli_fetch_assoc($fsresult);

    $sql = "SELECT * FROM `timetable` WHERE `event_code`='$event_code' AND `semester`='$semester' AND `church`='$church' AND `day`='$currentDay' AND `slot`='$slot' AND `year_level`='$year_level'"; 

    $result = mysqli_query($conn, $sql);

    $currentTimestamp = time();
    if ($result->num_rows == 1) {
        $studSQl = "SELECT * FROM `scholars` WHERE `semester`='$semester' AND `church`='$church' AND `year_level`='$year_level'"; 
        $studRes = mysqli_query($conn, $studSQl);
    } else {
        //     $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">

        // </div>';

        echo '<script>
        alert(" Event Not Allocated On ' . $currentDay . '!.");
        window.location.href ="manual_attend.php";
    </script>';
        // header("location: manual_attend.php");
        // exit();
    }
}
?>
<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-1 rounded-4" style="background: #eee;">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Attendance</li>
            <li class="breadcrumb-item">Manual Attendance</li>
        </ol>
    </nav>
</div>

<!-- Blank Start -->
<div class="container-fluid pt-4 px-4">
    <div class="text-center w-100">
        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>
    </div>

    <div class="row bg-light rounded mx-0">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Take Attendance</h6>

                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Event</td>
                            <td>Year Level</td>
                            <td>Semester</td>
                            <td>Church</td>
                            <td>Attendance Day</td>
                            <td>Slot</td>
                        </tr>
                        <tr>
                            <td><?php echo $fsrow['name']; ?></td>
                            <td><?php echo $year_level; ?></td>
                            <td><?php echo $semester; ?></td>
                            <td><?php echo $church; ?></td>
                            <td><?php echo $currentDay; ?></td>
                            <td><?php echo $slot; ?></td>
                        </tr>
                    </tbody>
                </table>

                <form action="api_manual_attend.php" method="post">
                <input class="form-control" type="text" name="year_level" value="<?php echo $batch; ?>" hidden> 
                <input class="form-control" type="text" name="semester" value="<?php echo $semester; ?>" hidden>
                <input class="form-control" type="text" name="church" value="<?php echo $branch; ?>" hidden>
                <input class="form-control" type="text" name="slot" value="<?php echo $slot; ?>" hidden>
                <input class="form-control" type="text" name="event_code" value="<?php echo $event_code; ?>" hidden> 
                <input class="form-control" type="text" id="currentDay" name="currentDay" value="<?php echo $currentDay; ?>" readonly hidden>
                <input class="form-control" type="text" id="clientIp" name="clientIp" readonly hidden>
                <div class="mb-3">
                    <label for="">Select Date</label>
                    <input class="form-control" type="date" name="date" required>
                </div>
                <p class="text-danger">*Please remember the following text: 'Select the date that matches the day of the week mentioned above.</p>
                <div class="mb-3">
                    <label for="">Select Time</label>
                    <input class="form-control" type="time" name="time" required>
                </div>

                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th scope="col">Mark Attendance</th>
                            <th scope="col">Name</th>
                            <th scope="col">Student No</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($studrow = mysqli_fetch_assoc($studRes)) {
                        ?>
                            <tr>
                                <td>
                                    <input class="form-check-input" type="checkbox" name="students[]" value="<?php echo $studrow['student_no']; ?>"> 
                                </td>
                                <td><?php echo $studrow['name']; ?></td>
                                <td><?php echo $studrow['student_no']; ?></td> 
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
            </div>
        </div>

    </div>
</div>
<!-- Blank End -->


<script>
    let clientIp = document.getElementById("clientIp");

    // Fetch the client's IP address
    $.get('https://api.ipify.org?format=json', function(data) {
        clientIp.value = data.ip;
    });
</script>

<?php
require('footer.php');
?>