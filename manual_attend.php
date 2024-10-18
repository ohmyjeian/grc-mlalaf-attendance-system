<?php
require('header.php');
require('conn.php');

if ($_SESSION['usertype'] != 'ADMIN') {
    session_destroy();
    header("location: login.php");
    exit();
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
                <h6 class="mb-4">Take Manual Attendance</h6>
                <form class="row gy-2 gx-3 align-items-center border p-2" action="manual_attend.php" method="get">
                    <div class="col-auto">
                        <p>View Time Table</p>
                    </div>

                    <div class="col-auto">
                        <label class="visually-hidden" for="autoSizingSelect">Preference</label>
                        <select class="form-select" name="academic" id="autoSizingSelect">
                            <option value="">Select Academic Year</option>
                            <option value="2024-2025">2024-2025</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="autoSizingSelect">Preference</label>
                        <select class="form-select" name="semester" id="autoSizingSelect">
                            <option value="">Select Semester</option>
                            <option value="1">First</option>
                            <option value="2">Second</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="autoSizingSelect">Preference</label>
                        <select class="form-select" name="church" id="autoSizingSelect">
                            <option value="">Select Church</option>
                            <option value="TEAM MBBEM">TEAM MBBEM</option>
                            <option value="TEAM FJC">TEAM FJC</option>
                            <option value="TEAM JTCC">TEAM JTCC</option>
                            <option value="TEAM GTC">TEAM GTC</option>
                            <option value="TEAM GEC">TEAM GEC</option>
                            <option value="TEAM PRAISE">TEAM PRAISE</option>
                            <option value="TEAM LWCC">TEAM LWCC</option>
                            <option value="TEAM CCF">TEAM CCF</option>
                            <option value="TEAM ZION">TEAM ZION</option>
                            <option value="TEAM SHEPHERDS">TEAM SHEPHERDS</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="autoSizingSelect">Preference</label>
                        <select class="form-select" name="year_level" id="autoSizingSelect">
                            <option value="">Select Year Level</option>
                            <option value="1">First</option>
                            <option value="2">Second</option>
                            <option value="3">Third</option>
                            <option value="4">Fourth</option>
                            <option value="5">Fifth</option>
                        </select>
                    </div>

                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-success">Search Time Table</button>
                    </div>
                </form>

                <?php
                if (isset($_GET['year_level']) && isset($_GET['semester']) && isset($_GET['academic']) && isset($_GET['church'])) {
                ?>

                    <h6 class="mb-3 text-center mt-3 text-danger">Time Table Details : Academic Year-[<?php echo $_GET['academic']; ?>], Church-[<?php echo $_GET['church']; ?>], Year Level-[<?php echo $_GET['year_level']; ?>], Semester-[<?php echo $_GET['semester']; ?>]</h6>

                    <!--div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Slot 1</th>
                                    <th scope="col">Slot 2</th>
                                    <th scope="col">Slot 3</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $semester = $_GET['semester'];
                                $year_level = $_GET['year_level'];
                                $academic = $_GET['academic'];
                                $church = $_GET['church'];
                                $sql = "SELECT * FROM `timetable` WHERE `academic_year`='$academic' AND `church`='$church' AND `semester`='$semester' AND `year_level`='$year_level'";
                                $result = mysqli_query($conn, $sql);
                                $daychanger = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    if ($daychanger == 1) {
                                ?>
                                        <tr>
                                            <td><?php echo $row['day']; ?></td>

                                <?php
                                    }

                                    $fssql = "SELECT * FROM `events` WHERE `event_code`='" . $row['event_code'] . "'";
                                    $fsresult = mysqli_query($conn, $fssql);
                                    $fsrow = mysqli_fetch_assoc($fsresult);
                                    echo '<td>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <p>' . $fsrow['name'];

                                    echo '<a href="give_manual_attend.php?event_code=' . $row['event_code'] . '&slot=' . $row['slot'] . '&year_level=' . $row['year_level'] . '&semester=' . $row['semester'] . '&church=' . $fsrow['church'] . '" title="Take Attendance"><i class="fas fa-clipboard-check ms-2"></i></a>';

                                    echo '</p>
                                            </div>
                                        </div>
                                    </td>';

                                    if ($daychanger == 3) {
                                ?>
                                        </tr>
                                <?php
                                    }
                                    if ($daychanger == 3) {
                                        $daychanger = 1;
                                    } else {
                                        $daychanger += 1;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> -->



                    <?php
                    $semester = mysqli_escape_string($conn, $_GET['semester']);
                    $year_level = mysqli_escape_string($conn, $_GET['year_level']);
                    $academic = mysqli_escape_string($conn, $_GET['academic']);
                    $church = mysqli_escape_string($conn, $_GET['church']);
                    $sql = "SELECT * FROM `timetable` WHERE `academic_year`='$academic' AND `church`='$church' AND `semester`='$semester' AND `year_level`='$year_level'";
                    $sqlslot = "SELECT DISTINCT `slot`,`slotlabel` FROM `timetable` WHERE `academic_year`='$academic' AND `church`='$church' AND `semester`='$semester' AND `year_level`='$year_level'";
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
                                                    $subjectSQL = "SELECT `name` FROM `events` WHERE `event_code`='$eventCode'"; 
                                                    $subjectRes = mysqli_query($conn, $subjectSQL);
                                                    $subjectRow = mysqli_fetch_assoc($subjectRes);
                                                    echo $subjectRow['name'];
                                                    echo '<a href="give_manual_attend.php?event_code=' . $eventCode . '&slot=' . $slot . '&year_level=' . $year_level . '&semester=' . $semester . '&church=' . $church . '&day=' . $day . '" title="Take Attendance"><i class="fas fa-clipboard-check ms-2"></i></a>';
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