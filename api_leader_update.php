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
            <li class="breadcrumb-item">Leaders</li>
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
        $sql = "SELECT * FROM teachers WHERE id='$enroll'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
    }
    ?>


    <div class="row bg-light rounded mx-0">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <form action="api_leader.php?type=update" method="post">
                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-3 col-form-label">Leader ID</label>
                        <div class="col-sm-9">
                            <input type="text" name="id" value="<?php echo $row['id']; ?>" readonly class="form-control" id="inputEmail3">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Leader Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="teachername" class="form-control" value="<?php echo $row['name']; ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Select Church Role</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="education" value="<?php echo $row['education']; ?>" required>
                                <option selected="">Open this select menu</option>
                                <option value="B.E/B.Tech" <?php if ($row['education'] == 'B.E/B.Tech') echo ' selected="selected"'; ?>>B.E/B.Tech</option>
                                <option value="M.E/M.Tech" <?php if ($row['education'] == 'M.E/M.Tech') echo ' selected="selected"'; ?>>M.E/M.Tech</option>
                                <option value="Ph.d" <?php if ($row['education'] == 'Ph.d') echo ' selected="selected"'; ?>>Ph.d</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Church</label>
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
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Select Designation</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="designation" value="<?php echo $row['designation']; ?>" required>
                                <option selected="">Open this select menu</option>
                                <option value="Instructor" <?php if ($row['designation'] == 'Instructor') echo ' selected="selected"'; ?>>Instructor</option>
                                <option value="Assistant Professor" <?php if ($row['designation'] == 'Assistant Professor') echo ' selected="selected"'; ?>>Assistant Professor</option>
                                <option value="Associate Professor" <?php if ($row['designation'] == 'Associate Professor') echo ' selected="selected"'; ?>>Associate Professor</option>
                                <option value="Professor" <?php if ($row['designation'] == 'Professor') echo ' selected="selected"'; ?>>Professor</option>
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