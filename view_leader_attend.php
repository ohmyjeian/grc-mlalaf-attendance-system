<?php
require('header.php');
require('conn.php');

if ($_SESSION['usertype'] != 'LEADER') {
    session_destroy();
    header("location: login.php");
    exit();
}

$leader_id = $_SESSION['leader_id'];

// Fetch the church associated with the leader
$leader_sql = "SELECT `church` FROM `leaders` WHERE `id` = $leader_id";
$leader_res = mysqli_query($conn, $leader_sql);
$leader_row = mysqli_fetch_assoc($leader_res);
$leader_church = $leader_row['church']; // Get the church associated with the leader

$subArrstr = "";

// Fetch the event codes without using `leader_id`
$ssql = "SELECT * FROM `events`"; // Removed `WHERE leader_id=$leader_id`
$sresult = mysqli_query($conn, $ssql);
while ($row = mysqli_fetch_assoc($sresult)) {
    if (empty($subArrstr)) {
        $subArrstr = $row['event_code'];
    } else {
        $subArrstr = $subArrstr . "," . $row['event_code'];
    }
}

// Fetch attendance based on event codes only, and filter by church
$sql = "SELECT * FROM `attendance` WHERE event_code IN ($subArrstr) AND church = '$leader_church' ORDER BY id DESC"; 
$result = mysqli_query($conn, $sql);
?>
<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-1 rounded-4" style="background: #eee;">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Attendance</li>
            <li class="breadcrumb-item">View Attendance</li>
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
                <h6 class="mb-4">View Attendance</h6>
                <form class="row gy-2 gx-3 align-items-center border p-2 mb-4" action="view_leader_attend.php" method="get">
                    <div class="col-auto d-flex">
                        <label class="" for="autoSizingSelect">Start Date</label>
                        <input type="date" name="startdate" date_format="yyyy-mm-dd" class="form-control" required>
                    </div>
                    <div class="col-auto d-flex">
                        <label class="" for="autoSizingSelect">End Date</label>
                        <input type="date" name="enddate" class="form-control" required>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="autoSizingSelect">Preference</label>
                        <select class="form-select" name="event" id="autoSizingSelect" required> 
                            <option value="">Select Event</option> 
                            <?php
                            // Removed filtering by leader_id
                            $sssql = "SELECT * FROM `events`"; 
                            $ssresult = mysqli_query($conn, $sssql);
                            while ($erow = mysqli_fetch_array($ssresult)) {
                                echo '<option value="' . $erow['event_code'] . '">' . $erow['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="autoSizingSelect">Year Level</label>
                        <select class="form-select" name="year_level" id="autoSizingSelect" required>
                            <option value="">Select Year Level</option> 
                            <option value="1">First</option>
                            <option value="2">Second</option>
                            <option value="3">Third</option>
                            <option value="4">Fourth</option>
                            <option value="5">Fifth</option>
                        </select>
                    </div>

                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-success">Search</button>
                    </div>
                </form>

                <?php
                if (isset($_GET['startdate']) && isset($_GET['enddate']) && isset($_GET['event']) && isset($_GET['year_level'])) { 
                    $sdateString = $_GET['startdate'];
                    $edateString = $_GET['enddate'];
                    $event_code = $_GET['event']; 
                    $year_level = $_GET['year_level']; 

                    // Adjusted the query to include church filtering
                    $sql = "SELECT * FROM attendance WHERE STR_TO_DATE(`date`, '%Y-%m-%d') BETWEEN STR_TO_DATE('$sdateString', '%Y-%m-%d') AND STR_TO_DATE('$edateString', '%Y-%m-%d') AND `event_code`='$event_code' AND `year_level`='$year_level' AND `church` = '$leader_church' ORDER BY id DESC";
                    $result = mysqli_query($conn, $sql);
                ?>
                    <h6>Filtered : Start Date= <?php echo $_GET['startdate']; ?> , End Date= <?php echo $_GET['enddate']; ?></h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center" id="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Student No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Day</th>
                                    <th scope="col">Event</th> 
                                    <th scope="col">Slot</th>
                                    <th scope="col">Year Level</th> 
                                    <th scope="col">Present at</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sr = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tr>
                                        <th scope="row"><?php echo $sr; ?></th>
                                        <td><?php echo $row['student_no']; ?></td>
                                        <td><?php
                                            $efssql = "SELECT * FROM `scholars` WHERE `student_no`='" . mysqli_real_escape_string($conn, $row['student_no']) . "'";
                                            $efsresult = mysqli_query($conn, $efssql);
                                            $efsrow = mysqli_fetch_assoc($efsresult);
                                            echo $efsrow['name'];
                                            ?></td>
                                        <td><?php echo $row['date']; ?></td>
                                        <td><?php echo $row['day']; ?></td>
                                        <td><?php
                                            $fssql = "SELECT * FROM `events` WHERE `event_code`='" . mysqli_real_escape_string($conn, $row['event_code']) . "'"; 
                                            $fsresult = mysqli_query($conn, $fssql);
                                            $fsrow = mysqli_fetch_assoc($fsresult);
                                            echo $fsrow['name'];
                                            ?></td>
                                        <td><?php echo $row['slot']; ?></td>
                                        <td><?php echo $row['year_level']; ?></td> 
                                        <td><?php echo $row['time']; ?></td>
                                    </tr>
                                <?php
                                    $sr++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php
                } else {
                ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center" id="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Student No</th> 
                                    <th scope="col">Name</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Day</th>
                                    <th scope="col">Event</th> 
                                    <th scope="col">Slot</th>
                                    <th scope="col">Year Level</th> 
                                    <th scope="col">Present at</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sr = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tr>
                                        <th scope="row"><?php echo $sr; ?></th>
                                        <td><?php echo $row['student_no']; ?></td> 
                                        <td>
                                            <?php
                                            $efssql = "SELECT * FROM `scholars` WHERE `student_no`='" . mysqli_real_escape_string($conn, $row['student_no']) . "'";
                                            $efsresult = mysqli_query($conn, $efssql);
                                            $efsrow = mysqli_fetch_assoc($efsresult);
                                            echo $efsrow['name'];
                                            ?>
                                        </td>
                                        <td><?php echo $row['date']; ?></td>
                                        <td><?php echo $row['day']; ?></td>
                                        <td><?php
                                            $fssql = "SELECT * FROM `events` WHERE `event_code`='" . mysqli_real_escape_string($conn, $row['event_code']) . "'";
                                            $fsresult = mysqli_query($conn, $fssql);
                                            $fsrow = mysqli_fetch_assoc($fsresult);
                                            echo $fsrow['name'];
                                            ?></td> 
                                        <td><?php echo $row['slot']; ?></td>
                                        <td><?php echo $row['year_level']; ?></td> 
                                        <td><?php echo $row['time']; ?></td>
                                    </tr>
                                <?php
                                    $sr++;
                                }
                                ?>
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
