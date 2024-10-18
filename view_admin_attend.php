<?php
require('header.php');
require('conn.php');

if ($_SESSION['usertype'] != 'ADMIN') {
    session_destroy();
    header("location: login.php");
    exit();
}

$sql = "SELECT * FROM `attendance` ORDER BY id DESC";
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
                <form class="row gy-2 gx-3 align-items-center border p-2 mb-4" action="view_admin_attend.php" method="get">
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
                            $sssql = "SELECT * FROM `events`";
                            $ssresult = mysqli_query($conn, $sssql);
                            while ($erow = mysqli_fetch_array($ssresult)) {
                                echo '<option value="' . $erow['event_code'] . '">' . $erow['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="autoSizingSelect">Preference</label>
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
                        <label class="visually-hidden" for="autoSizingSelect">Preference</label>
                        <select class="form-select" name="church" id="autoSizingSelect" required>
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
                        <button type="submit" class="btn btn-outline-success">Search</button>
                    </div>
                </form>

                <?php
                if (isset($_GET['startdate']) && isset($_GET['enddate']) && isset($_GET['event']) && isset($_GET['year_level']) && isset($_GET['church'])) {
                    $sdateString = $_GET['startdate'];
                    $edateString = $_GET['enddate'];
                    $event_code = $_GET['event'];
                    $year_level = $_GET['year_level'];
                    $church = $_GET['church'];

                    // Updated SQL query to match the new column names
                    $sql = "SELECT * FROM attendance WHERE STR_TO_DATE(`date`, '%Y-%m-%d') BETWEEN STR_TO_DATE('$sdateString', '%Y-%m-%d') AND STR_TO_DATE('$edateString', '%Y-%m-%d') AND `event_code`=$event_code AND `year_level`='$year_level' AND `church`='$church' ORDER BY id DESC";
                    $result = mysqli_query($conn, $sql);
                ?>
                    <h6>Filtered : Start Date= <?php echo $_GET['startdate']; ?> , End Date= <?php echo $_GET['enddate']; ?></h6>
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
                                <th scope="col">Checked in at [Time]</th>
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
                                    // Enclose student_no in quotes since it's a VARCHAR
                                    $efssql = "SELECT * FROM `scholars` WHERE `student_no`='" . mysqli_real_escape_string($conn, $row['student_no']) . "'";
                                    $efsresult = mysqli_query($conn, $efssql);

                                    // Check if the query was successful
                                    if ($efsresult) {
                                        $efsrow = mysqli_fetch_assoc($efsresult);
                                        // Check if a row was returned
                                        if ($efsrow) {
                                            echo $efsrow['name']; // Adjust based on the actual column name
                                        } else {
                                            echo "No name found"; // You can leave it empty if preferred
                                        }
                                    } else {
                                        echo "Query failed: " . mysqli_error($conn); // Display error if query fails
                                    }
                                    ?>
                                </td>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['day']; ?></td>
                                <td>
                                    <?php
                                    // Enclose event_code in quotes since it's a VARCHAR
                                    $fssql = "SELECT * FROM `events` WHERE `event_code`='" . mysqli_real_escape_string($conn, $row['event_code']) . "'";
                                    $fsresult = mysqli_query($conn, $fssql);

                                    // Check if the query was successful
                                    if ($fsresult) {
                                        $fsrow = mysqli_fetch_assoc($fsresult);
                                        // Check if a row was returned
                                        if ($fsrow) {
                                            echo $fsrow['name']; // Adjust based on the actual column name
                                        } else {
                                            echo "No event found"; // You can leave it empty if preferred
                                        }
                                    } else {
                                        echo "Query failed: " . mysqli_error($conn); // Display error if query fails
                                    }
                                    ?>
                                </td>
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
                <?php
                } else {
                ?>
                    <table class="table table-bordered table-striped text-center" id="table">
                        <thead>
                            <tr>
                                <th scope="col">Sr.No</th>
                                <th scope="col">Student No</th>
                                <th scope="col">Name</th>
                                <th scope="col">Date</th>
                                <th scope="col">Day</th>
                                <th scope="col">Event</th>
                                <th scope="col">Slot</th>
                                <th scope="col">Year Level</th>
                                <th scope="col">Checked in at [Time]</th>
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
                                    // Enclose student_no in quotes since it's a VARCHAR
                                    $efssql = "SELECT * FROM `scholars` WHERE `student_no`='" . mysqli_real_escape_string($conn, $row['student_no']) . "'";
                                    $efsresult = mysqli_query($conn, $efssql);

                                    // Check if the query was successful
                                    if ($efsresult) {
                                        $efsrow = mysqli_fetch_assoc($efsresult);
                                        // Check if a row was returned
                                        if ($efsrow) {
                                            echo $efsrow['name']; // Use the correct column name
                                        } else {
                                            echo "No name found"; // Or leave empty if preferred
                                        }
                                    } else {
                                        echo "Query failed: " . mysqli_error($conn); // Display error if query fails
                                    }
                                    ?>
                                </td>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['day']; ?></td>
                                <td>
                                    <?php
                                    // Enclose event_code in quotes since it's a VARCHAR
                                    $fssql = "SELECT * FROM `events` WHERE `event_code`='" . mysqli_real_escape_string($conn, $row['event_code']) . "'";
                                    $fsresult = mysqli_query($conn, $fssql);

                                    // Check if the query was successful
                                    if ($fsresult) {
                                        $fsrow = mysqli_fetch_assoc($fsresult);
                                        // Check if a row was returned
                                        if ($fsrow) {
                                            echo $fsrow['name']; // Use the correct column name
                                        } else {
                                            echo "No event found"; // Or leave empty if preferred
                                        }
                                    } else {
                                        echo "Query failed: " . mysqli_error($conn); // Display error if query fails
                                    }
                                    ?>
                                </td>
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
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
require('footer.php');
?>
