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
            <li class="breadcrumb-item">Students</li>
            <li class="breadcrumb-item">Details</li>
            <li class="breadcrumb-item active" aria-current="page">Update</li>
        </ol>
    </nav>
</div>


<!-- Blank Start -->
<div class="container-fluid pt-4 px-4">
    <?php
    if (isset($_GET['student_no'])) {
        $student_no = mysqli_escape_string($conn, $_GET['student_no']);
        $sql = "SELECT * FROM scholars WHERE student_no='$student_no'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
    }
    ?>


    <div class="row bg-light rounded mx-0">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <form action="api_stud.php?type=update" method="post">
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-3 col-form-label">Student No</label>
                        <div class="col-sm-9">
                            <input type="text" name="student_no" class="form-control" value="<?php echo $student_no; ?>" id="inputEmail3" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Student Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="studentname" value="<?php echo $row['name']; ?>" class="form-control" id="inputPassword3">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Select Semester</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="semester" required>
                                <option selected="">Open this select menu</option>
                                <option value="1" <?php if ($row['semester'] == '1') echo ' selected="selected"'; ?>>First</option>
                                <option value="2" <?php if ($row['semester'] == '2') echo ' selected="selected"'; ?>>Second</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Church</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="church" required>
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
                            <input type="text" name="rollno" class="form-control" value="<?php echo $row['roll_no']; ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Select Year Level</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="year_level" required>
                                <option selected="">Open this select menu</option>
                                <option value="1" <?php if ($row['year_level'] == '1') echo ' selected="selected"'; ?>>First</option>
                                <option value="2" <?php if ($row['year_level'] == '2') echo ' selected="selected"'; ?>>Second</option>
                                <option value="3" <?php if ($row['year_level'] == '3') echo ' selected="selected"'; ?>>Third</option>
                                <option value="4" <?php if ($row['year_level'] == '4') echo ' selected="selected"'; ?>>Fourth</option>
                                <option value="5" <?php if ($row['year_level'] == '5') echo ' selected="selected"'; ?>>Fifth</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9">
                            <input type="text" name="password" class="form-control" value="<?php echo $row['password']; ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Blank End -->


<?php
require('footer.php');
?>