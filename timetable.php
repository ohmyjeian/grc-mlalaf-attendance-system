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
            <li class="breadcrumb-item">Time Table</li>
            <li class="breadcrumb-item active" aria-current="page">Details</li>
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
        <button type="button" class="btn btn-outline-primary m-2" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fas fa-plus me-2"></i>Set New Time Table</button>
        <button type="button" class="btn btn-outline-danger m-2" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fas fa-plus me-2"></i>Delete Time Table</button>
    </div>

    <div class="row bg-light rounded mx-0">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Time Table</h6>
                <form class="row gy-2 gx-3 align-items-center border p-2" action="timetable.php" method="get">
                    <div class="col-auto">
                        <p>View Time Table</p>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="autoSizingSelect">Church</label>
                        <select class="form-select" name="church" required>
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
                        <label class="visually-hidden" for="autoSizingSelect">Semester</label>
                        <select class="form-select" name="semester" id="autoSizingSelect">
                            <option value="">Select Semester</option>
                            <option value="1">First</option>
                            <option value="2">Second</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="autoSizingSelect">Year Level</label>
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
                        <label class="visually-hidden" for="autoSizingSelect">Academic Year</label>
                        <select class="form-select" name="academic" id="autoSizingSelect">
                            <option value="">Select Academic Year</option>
                            <option value="2024-2025">2024-2025</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-success">Search Time Table</button>
                    </div>
                </form>


                <?php
                if (isset($_GET['year_level']) && isset($_GET['semester']) && isset($_GET['academic']) && isset($_GET['church'])) {
                ?>

                    <h6 class="mb-3 text-center mt-3 text-danger">Time Table Details : Academic Year-[<?php echo $_GET['academic']; ?>], Church-[<?php echo $_GET['church']; ?>] , Year Level-[<?php echo $_GET['year_level']; ?>], Semester-[<?php echo $_GET['semester']; ?>]</h6>

                    <?php
                    $semester = mysqli_escape_string($conn, $_GET['semester']);
                    $year_level = mysqli_escape_string($conn, $_GET['year_level']);
                    $academic = mysqli_escape_string($conn, $_GET['academic']);
                    $church = mysqli_escape_string($conn, $_GET['church']);
                    $sql = "SELECT * FROM `timetable` WHERE `academic_year`='$academic' AND `church`='$church' AND `semester`='$semester' AND `year_level`='$year_level'";
                    $sqlslot = "SELECT DISTINCT `slotlabel` FROM `timetable` WHERE `academic_year`='$academic' AND `church`='$church' AND `semester`='$semester' AND `year_level`='$year_level'";
                    $result1 = mysqli_query($conn, $sqlslot);
                    $result2 = mysqli_query($conn, $sql);

                    $slots = [];
                    while ($row = mysqli_fetch_assoc($result1)) {
                        $slots[] = $row['slotlabel'];
                    }

                    $timetable = [];
                    while ($row = mysqli_fetch_assoc($result2)) {
                        $timetable[$row['day']][] = $row['event_code'];
                    }

                    ?>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Day</th>
                                    <?php foreach ($slots as $slot) : ?>
                                        <th scope="col"><?php echo $slot; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                $slotCount = count($slots);

                                foreach ($daysOfWeek as $day) :
                                    $dayData = isset($timetable[$day]) ? $timetable[$day] : array_fill(0, $slotCount, '');
                                ?>
                                    <tr>
                                        <td><?php echo $day; ?></td>
                                        <?php for ($i = 0; $i < $slotCount; $i++) : ?>
                                            <td><?php
                                                $eventCode =  isset($dayData[$i]) ? $dayData[$i] : '';
                                                $eventSQL = "SELECT * FROM `events` WHERE `event_code`='$eventCode'";
                                                $eventRes = mysqli_query($conn, $eventSQL);
                                                $eventRow = mysqli_fetch_assoc($eventRes);
                                                echo $eventRow['name'];
                                                ?></td>
                                        <?php endfor; ?>
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



<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Set New Time Table</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="bg-light rounded h-100 p-4">
                        <form action="api_timetable.php?type=add" method="post">
                            <div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Academic Year</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="academicyear" id="floatingSelect" aria-label="Floating label select example" required>
                                            <option value="">Open this select menu</option>
                                            <option value="2024-2025">2024-2025</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Church</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="church" id="floatingSelect" aria-label="Floating label select example" required>
                                            <option value="">Open this select menu</option>
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
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Semester</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="semester" id="floatingSelect" aria-label="Floating label select example" required>
                                            <option value="">Open this select menu</option>
                                            <option value="1">First</option>
                                            <option value="2">Second</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Year Level</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="year_level" id="floatingSelect" aria-label="Floating label select example" required>
                                            <option value="">Open this select menu</option>
                                            <option value="1">First</option>
                                            <option value="2">Second</option>
                                            <option value="3">Third</option>
                                            <option value="4">Fourth</option>
                                            <option value="5">Fifth</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 justify-content-end">
                                <input type="text" class="form-control form-control-sm" placeholder="Slot Label" id="slotlabelinput">
                                <button class="btn btn-dark btn-sm" onclick="addslot()" type="button">Add Slot</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-borderless text-center">
                                    <thead>
                                        <tr id="slotlabelbox">
                                            <th></th>

                                            </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="mondaySlotsBox">
                                            <td>Monday</td>
                                        </tr>

                                        <tr id="tuesdaySlotsBox">
                                            <td>Tuesday</td>

                                        </tr>

                                        <tr id="wednesdaySlotsBox">
                                            <td>Wednesday</td>

                                        </tr>

                                        <tr id="thursdaySlotsBox">
                                            <td>Thursday</td>

                                        </tr>

                                        <tr id="fridaySlotsBox">
                                            <td>Friday</td>

                                        </tr>

                                        <tr id="saturdaySlotsBox">
                                            <td>Saturday</td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Time Table</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="bg-light rounded h-100 p-4">
                        <form action="api_timetable.php?type=delete" method="post">
                            <div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Church</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="church" id="floatingSelect" aria-label="Floating label select example" required>
                                            <option value="">Open this select menu</option>
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
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Academic Year</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="academic_year" id="floatingSelect" aria-label="Floating label select example" required>
                                            <option value="">Open this select menu</option>
                                            <option value="2024-2025">2024-2025</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Semester</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="semester" id="floatingSelect" aria-label="Floating label select example" required>
                                            <option value="">Open this select menu</option>
                                            <option value="1">First</option>
                                            <option value="2">Second</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-3 col-form-label">Select Year Level</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="year_level" id="floatingSelect" aria-label="Floating label select example" required>
                                            <option value="">Open this select menu</option>
                                            <option value="1">First</option>
                                            <option value="2">Second</option>
                                            <option value="3">Third</option>
                                            <option value="4">Fourth</option>
                                            <option value="5">Fifth</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Delete</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    function addslot() {
        let timelabel = $('#slotlabelinput').val();
        if (timelabel == "") {
            alert("Add Time Label");
        } else {
            $('#slotlabelbox').append(`<th scope="col">${timelabel} <input type="text" readonly value="${timelabel}" name="slots[]" hidden></th>`);

            $('#mondaySlotsBox').append(`<td>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <select class="form-select" name="Monday[]" id="floatingSelect" aria-label="Floating label select example" required>
                                                            <option value="">Select Event</option>
                                                            <?php
                                                            $tsql = "SELECT * FROM events"; 
                                                            $tresult = mysqli_query($conn, $tsql);
                                                            while ($trow = mysqli_fetch_assoc($tresult)) {
                                                            ?>
                                                                <option value="<?php echo $trow['event_code']; ?>"><?php echo $trow['name']; ?></option> <!-- Updated from subject_code to event_code -->
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
`);
            $('#tuesdaySlotsBox').append(`<td>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <select class="form-select" name="Tuesday[]" id="floatingSelect" aria-label="Floating label select example" required>
                                                            <option value="">Select Event</option>
                                                            <?php
                                                            $tsql = "SELECT * FROM events"; 
                                                            $tresult = mysqli_query($conn, $tsql);
                                                            while ($trow = mysqli_fetch_assoc($tresult)) {
                                                            ?>
                                                                <option value="<?php echo $trow['event_code']; ?>"><?php echo $trow['name']; ?></option> <!-- Updated from subject_code to event_code -->
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
`);
            $('#wednesdaySlotsBox').append(`<td>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <select class="form-select" name="Wednesday[]" id="floatingSelect" aria-label="Floating label select example" required>
                                                            <option value="">Select Event</option>
                                                            <?php
                                                            $tsql = "SELECT * FROM events"; 
                                                            $tresult = mysqli_query($conn, $tsql);
                                                            while ($trow = mysqli_fetch_assoc($tresult)) {
                                                            ?>
                                                                <option value="<?php echo $trow['event_code']; ?>"><?php echo $trow['name']; ?></option> <!-- Updated from subject_code to event_code -->
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
`);
            $('#thursdaySlotsBox').append(`<td>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <select class="form-select" name="Thursday[]" id="floatingSelect" aria-label="Floating label select example" required>
                                                            <option value="">Select Event</option>
                                                            <?php
                                                            $tsql = "SELECT * FROM events";
                                                            $tresult = mysqli_query($conn, $tsql);
                                                            while ($trow = mysqli_fetch_assoc($tresult)) {
                                                            ?>
                                                                <option value="<?php echo $trow['event_code']; ?>"><?php echo $trow['name']; ?></option> <!-- Updated from subject_code to event_code -->
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
`);
            $('#fridaySlotsBox').append(`<td>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <select class="form-select" name="Friday[]" id="floatingSelect" aria-label="Floating label select example" required>
                                                            <option value="">Select Event</option>
                                                            <?php
                                                            $tsql = "SELECT * FROM events"; 
                                                            $tresult = mysqli_query($conn, $tsql);
                                                            while ($trow = mysqli_fetch_assoc($tresult)) {
                                                            ?>
                                                                <option value="<?php echo $trow['event_code']; ?>"><?php echo $trow['name']; ?></option> <!-- Updated from subject_code to event_code -->
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
`);
            $('#saturdaySlotsBox').append(`<td>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <select class="form-select" name="Saturday[]" id="floatingSelect" aria-label="Floating label select example" required>
                                                            <option value="">Select Event</option>
                                                            <?php
                                                            $tsql = "SELECT * FROM events"; 
                                                            $tresult = mysqli_query($conn, $tsql);
                                                            while ($trow = mysqli_fetch_assoc($tresult)) {
                                                            ?>
                                                                <option value="<?php echo $trow['event_code']; ?>"><?php echo $trow['name']; ?></option> <!-- Updated from subject_code to event_code -->
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
`);

            $('#slotlabelinput').val('');
        }
    }
</script>

<?php
require('footer.php');
?>