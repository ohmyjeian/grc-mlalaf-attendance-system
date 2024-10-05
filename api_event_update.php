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
            <li class="breadcrumb-item">Subjects</li>
            <li class="breadcrumb-item">Details</li>
            <li class="breadcrumb-item active" aria-current="page">Update</li>
        </ol>
    </nav>
</div>


<!-- Blank Start -->
<div class="container-fluid pt-4 px-4">
    <?php
    if (isset($_GET['enroll'])) {
        $enroll = mysqli_escape_string($conn, $_GET['enroll']);
        $sql = "SELECT * FROM subjects WHERE subject_code='$enroll'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
    }
    ?>


    <div class="row bg-light rounded mx-0">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <form action="api_event.php?type=update" method="post">
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-3 col-form-label">Subject Code</label>
                        <div class="col-sm-9">
                            <input type="number" name="subjectcode" value="<?php echo $row['subject_code']; ?>" readonly class="form-control" id="inputEmail3">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Subject Description</label>
                        <div class="col-sm-9">
                            <input type="text" name="description" class="form-control" value="<?php echo $row['name']; ?>" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Subject Abbrevation</label>
                        <div class="col-sm-9">
                            <input type="text" name="abbrevation" class="form-control" value="<?php echo $row['abbreviation']; ?>" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Select Semeter</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="semester" value="<?php echo $row['semester']; ?>" required>
                                <option selected="">Open this select menu</option>
                                <option value="1" <?php if ($row['semester'] == '1') echo ' selected="selected"'; ?>>First</option>
                                <option value="2" <?php if ($row['semester'] == '2') echo ' selected="selected"'; ?>>Second</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Branch</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="branch" value="<?php echo $row['branch']; ?>" required>
                                <option selected="">Open this select menu</option>
                                <option value="TEAM MBBEM" <?php if ($row['branch'] == 'TEAM MBBEM') echo ' selected="selected"'; ?>>TEAM MBBEM</option>
                                <option value="TEAM FJC" <?php if ($row['branch'] == 'TEAM FJC') echo ' selected="selected"'; ?>>TEAM FJC</option>
                                <option value="TEAM JTCC" <?php if ($row['branch'] == 'TEAM JTCC') echo ' selected="selected"'; ?>>TEAM JTCC</option>
                                <option value="TEAM GTC" <?php if ($row['branch'] == 'TEAM GTC') echo ' selected="selected"'; ?>>TEAM GTC</option>
                                <option value="TEAM GEC" <?php if ($row['branch'] == 'TEAM GEC') echo ' selected="selected"'; ?>>TEAM GEC</option>
                                <option value="TEAM PRAISE" <?php if ($row['branch'] == 'TEAM PRAISE') echo ' selected="selected"'; ?>>TEAM PRAISE</option>
                                <option value="TEAM LWCC" <?php if ($row['branch'] == 'TEAM LWCC') echo ' selected="selected"'; ?>>TEAM LWCC</option>
                                <option value="TEAM CCF" <?php if ($row['branch'] == 'TEAM CCF') echo ' selected="selected"'; ?>>TEAM CCF</option>
                                <option value="TEAM ZION" <?php if ($row['branch'] == 'TEAM ZION') echo ' selected="selected"'; ?>>TEAM ZION</option>
                                <option value="TEAM SHEPHERDS" <?php if ($row['branch'] == 'TEAM SHEPHERDS') echo ' selected="selected"'; ?>>TEAM SHEPHERDS</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Taught By</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="teacherid" value="<?php echo $row['id']; ?>" readonly required>
                                <option selected="">Open this select menu</option>
                                <?php
                                $tsql = "SELECT * FROM teachers";
                                $tresult = mysqli_query($conn, $tsql);
                                while ($trow = mysqli_fetch_assoc($tresult)) {
                                ?>
                                    <option value="<?php echo $trow['id']; ?>" <?php if ($row['teacher_id'] == $trow['id']) echo ' selected="selected"'; ?>><?php echo $trow['name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
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