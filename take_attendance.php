<?php
require('header.php');
require('conn.php');

if ($_SESSION['usertype'] != 'LEADER') { 
    session_destroy();
    header("location: login.php");
    exit();
}

$leader_id = $_SESSION['leader_id']; 
$leader_sql = "SELECT * FROM leaders WHERE `id` = $leader_id"; 
$leader_res = mysqli_query($conn, $leader_sql);
$leader_row = mysqli_fetch_assoc($leader_res);
?>
<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-1 rounded-4" style="background: #eee;">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Attendance</li>
            <li class="breadcrumb-item active" aria-current="page">Take Attendance</li>
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
                <form class="row gy-2 gx-3 align-items-center border p-2" action="take_attendance.php" method="get">
                    <div class="col-auto">
                        <p>View Time Table</p>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="semesterSelect">Semester</label>
                        <select class="form-select" name="semester" id="semesterSelect">
                            <option value="">Select Semester</option>
                            <option value="1">First</option>
                            <option value="2">Second</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="yearLevelSelect">Year Level</label>
                        <select class="form-select" name="year_level" id="yearLevelSelect"> 
                            <option value="">Select Year Level</option>
                            <option value="1">First</option>
                            <option value="2">Second</option>
                            <option value="3">Third</option>
                            <option value="4">Fourth</option> 
                            <option value="5">Fifth</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="academicSelect">Academic Year</label>
                        <select class="form-select" name="academic" id="academicSelect">
                            <option value="">Select Academic Year</option>
                            <option value="2024-2025">2024-2025</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-success">Search Time Table</button>
                    </div>
                </form>

                <?php
                if (isset($_GET['year_level']) && isset($_GET['semester']) && isset($_GET['academic'])) { 
                    $semester = mysqli_escape_string($conn, $_GET['semester']);
                    $year_level = mysqli_escape_string($conn, $_GET['year_level']); 
                    $academic = mysqli_escape_string($conn, $_GET['academic']);
                    
                    // Fetch timetable data
                    $sql = "SELECT * FROM `timetable` WHERE `academic_year` = '$academic' AND `semester` = '$semester' AND `year_level` = '$year_level'";
                    $sqlslot = "SELECT DISTINCT `slot`, `slotlabel` FROM `timetable` WHERE `academic_year` = '$academic' AND `semester` = '$semester' AND `year_level` = '$year_level'";
                    $result1 = mysqli_query($conn, $sqlslot);
                    $result2 = mysqli_query($conn, $sql);

                    $slots = [];
                    while ($row = mysqli_fetch_assoc($result1)) {
                        $slots[$row['slot']] = $row['slotlabel'];
                    }

                    $timetable = [];
                    while ($row = mysqli_fetch_assoc($result2)) {
                        $day = $row['day'];
                        $slot = $row['slot'];
                        if (!isset($timetable[$day])) {
                            $timetable[$day] = [];
                        }
                        $timetable[$day][$slot] = $row['event_code']; 
                    }
                ?>

                    <h6 class="mb-3 text-center mt-3 text-danger">Time Table Details : Academic Year-[<?php echo $academic; ?>], Year Level-[<?php echo $year_level; ?>], Semester-[<?php echo $semester; ?>]</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Day</th>
                                    <?php foreach ($slots as $slot => $slotLabel) : ?>
                                        <th scope="col"><?php echo $slotLabel; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                foreach ($daysOfWeek as $day) :
                                    $dayData = isset($timetable[$day]) ? $timetable[$day] : [];
                                ?>
                                    <tr>
                                        <td><?php echo $day; ?></td>
                                        <?php foreach ($slots as $slot => $slotLabel) : ?>
                                            <td>
                                                <?php
                                                $eventCode = isset($dayData[$slot]) ? $dayData[$slot] : ''; 
                                                if ($eventCode) {
                                                    // Query the event name based on the event code
                                                    $subjectSQL = "SELECT `name` FROM `events` WHERE `event_code` = '$eventCode'"; 
                                                    $subjectRes = mysqli_query($conn, $subjectSQL);
                                                    $subjectRow = mysqli_fetch_assoc($subjectRes);
                                                    echo $subjectRow['name'];

                                                    // Allow attendance taking if it’s the current leader’s event and the current day matches
                                                    if ($day == date('l')) {
                                                        echo '<a href="take_attend.php?event_code=' . $eventCode . '&slot=' . $slot . '&year_level=' . $year_level . '&day=' . $day . '&semester=' . $semester . '&slotlabel=' . $slotLabel . '" title="Take Attendance"><i class="fas fa-clipboard-check ms-2"></i></a>';
                                                    }
                                                }
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Blank End -->

<?php
require('footer.php');
?>
