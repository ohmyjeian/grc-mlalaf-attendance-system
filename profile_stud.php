<?php
require('header.php');
require('conn.php');

if ($_SESSION['usertype'] != 'SCHOLAR') {
    session_destroy();
    header("location: login.php");
    exit();
}

$student_no = $_SESSION['student_no'];
$sql = "SELECT * FROM scholars WHERE `student_no`= '$student_no'";
$result = mysqli_query($conn, $sql);
$row = null;

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
}
?>

<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded mx-0">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <form action="api_self_stud_update.php" method="post" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Profile Picture</label>
                        <div id="piceditbox" class="col-sm-9" hidden>
                            <input type="file" name="pic" class="form-control">
                        </div>
                        <div id="picshow" class="col-sm-9">
                            <img src="./img/profile/<?php echo isset($row['pic']) ? $row['pic'] : 'default.png'; ?>" alt="No image upload" width="150px" height="150px">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Student No</label>
                        <div class="col-sm-9">
                            <input type="text" name="student_no" class="form-control" value="<?php echo $student_no; ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Student Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="student_name" value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Select Semester</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="semester" required disabled>
                                <option value="1" <?php if (isset($row['semester']) && $row['semester'] == '1') echo 'selected'; ?>>First</option>
                                <option value="2" <?php if (isset($row['semester']) && $row['semester'] == '2') echo 'selected'; ?>>Second</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Church</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="church" name="church" required disabled>
                                <option selected="">Open this select menu</option>
                                <option value="TEAM MBBEM" <?php if ($row['church'] == 'TEAM MBBEM') echo ' selected="selected"'; ?>>TEAM MBBEM</option>
                                <option value="TEAM FJC" <?php if ($row['church'] == 'TEAM FJC') echo ' selected="selected"'; ?>>TEAM FJC</option>
                                <option value="TEAM JTCC" <?php if ($row['church'] == 'TEAM JTCC') echo ' selected="selected"'; ?>>TEAM JTCC</option>
                                <option value="TEAM GTC" <?php if ($row['church'] == 'TEAM GTC') echo ' selected="selected"'; ?>>TEAM GTC</option>
                                <option value="TEAM GEC" <?php if ($row['church'] == 'TEAM GEC') echo ' selected="selected"'; ?>>TEAM GEC</option>
                                <option value="TEAM PRAISE" <?php if ($row['church'] == 'TEAM PRAISE') echo ' selected="selected"'; ?>>TEAM PRAISE</option>
                                <option value="TEAM LWCC" <?php if ($row['church'] == 'TEAM LWCC') echo ' selected="selected"'; ?>>TEAM LWCC</option>
                                <option value="TEAM CCF" <?php if ($row['church'] == 'TEAM CCF') echo ' selected="selected"'; ?>>TEAM CCF</option>
                                <option value="TEAM ZION" <?php if ($row['church'] == 'TEAM ZION') echo ' selected="selected"'; ?>>TEAM ZION</option>
                                <option value="TEAM SHEPHERDS" <?php if ($row['church'] == 'TEAM SHEPHERDS') echo ' selected="selected"'; ?>>TEAM SHEPHERDS</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Assign Roll No</label>
                        <div class="col-sm-9">
                            <input type="text" name="rollno" class="form-control" value="<?php echo $row['roll_no']; ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Select Year Level</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="year_level" required disabled>
                                <option selected="">Open this select menu</option>
                                <option value="1" <?php if ($row['year_level'] == '1') echo ' selected="selected"'; ?>>First</option>
                                <option value="2" <?php if ($row['year_level'] == '2') echo ' selected="selected"'; ?>>Second</option>
                                <option value="3" <?php if ($row['year_level'] == '3') echo ' selected="selected"'; ?>>Third</option>
                                <option value="4" <?php if ($row['year_level'] == '4') echo ' selected="selected"'; ?>>Fourth</option>
                                <option value="5" <?php if ($row['year_level'] == '5') echo ' selected="selected"'; ?>>Fifth</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex ">
                        <button type="button" class="btn btn-secondary w-100 m-2" onclick="edit()">Edit</button>
                        <button type="submit" class="btn btn-primary w-100 m-2" id="formbtn" hidden>Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function edit() {
        $('#piceditbox').removeAttr("hidden");
        $('#student_name').removeAttr("readonly");
        $('#picshow').attr("hidden", true);
        $('#formbtn').removeAttr("hidden");
    }
</script>

<?php 
require('footer.php'); 
?>