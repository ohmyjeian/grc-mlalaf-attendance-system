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
    if (isset($_GET['id'])) {
        $leader_id = mysqli_escape_string($conn, $_GET['id']);
        $sql = "SELECT * FROM `leaders` WHERE `id`='$leader_id'";
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
                            <input type="text" name="leadername" class="form-control" value="<?php echo $row['name']; ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Select Church Role</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="church_role" required>
                                <option selected="">Open this select menu</option>
                                <option value="Pastor" <?php if ($row['church_role'] == 'Pastor') echo ' selected="selected"'; ?>>Pastor</option>
                                <option value="Youth Pastor" <?php if ($row['church_role'] == 'Youth Pastor') echo ' selected="selected"'; ?>>Youth Pastor</option>
                                <option value="Primary Leader" <?php if ($row['church_role'] == 'Primary Leader') echo ' selected="selected"'; ?>>Primary Leader</option>
                                <option value="Youth Leader" <?php if ($row['church_role'] == 'Youth Leader') echo ' selected="selected"'; ?>>Youth Leader</option>
                                <option value="Cell Leader" <?php if ($row['church_role'] == 'Cell Leader') echo ' selected="selected"'; ?>>Cell Leader</option>
                                <option value="Worship Leader" <?php if ($row['church_role'] == 'Worship Leader') echo ' selected="selected"'; ?>>Worship Leader</option>
                                <option value="Prayer Leader" <?php if ($row['church_role'] == 'Prayer Leader') echo ' selected="selected"'; ?>>Prayer Leader</option>
                                <option value="Choir Member" <?php if ($row['church_role'] == 'Choir Member') echo ' selected="selected"'; ?>>Choir Member</option>
                                <option value="Musician" <?php if ($row['church_role'] == 'Musician') echo ' selected="selected"'; ?>>Musician</option>
                                <option value="Sound Technician" <?php if ($row['church_role'] == 'Sound Technician') echo ' selected="selected"'; ?>>Sound Technician</option>
                                <option value="Lighting Technician" <?php if ($row['church_role'] == 'Lighting Technician') echo ' selected="selected"'; ?>>Lighting Technician</option>
                                <option value="Media Technician" <?php if ($row['church_role'] == 'Media Technician') echo ' selected="selected"'; ?>>Media Technician</option>
                                <option value="Video Operator" <?php if ($row['church_role'] == 'Video Operator') echo ' selected="selected"'; ?>>Video Operator</option>
                                <option value="Graphic Designer" <?php if ($row['church_role'] == 'Graphic Designer') echo ' selected="selected"'; ?>>Graphic Designer</option>
                                <option value="Usher" <?php if ($row['church_role'] == 'Usher') echo ' selected="selected"'; ?>>Usher</option>
                                <option value="Hospitality Team Member" <?php if ($row['church_role'] == "Hospitality Team Member") echo ' selected="selected"'; ?>>Hospitality Team Member</option>
                                <option value="Children's Ministry Worker" <?php if ($row['church_role'] == "Children's Ministry Worker") echo ' selected="selected"'; ?>>Children's Ministry Worker</option>
                                <option value="Creative Arts Director" <?php if ($row['church_role'] == 'Creative Arts Director') echo ' selected="selected"'; ?>>Creative Arts Director</option>
                                <option value="Social Media Manager" <?php if ($row['church_role'] == 'Social Media Manager') echo ' selected="selected"'; ?>>Social Media Manager</option>
                                <option value="Website Administrator" <?php if ($row['church_role'] == 'Website Administrator') echo ' selected="selected"'; ?>>Website Administrator</option>
                                <option value="Dance Team Member" <?php if ($row['church_role'] == 'Dance Team Member') echo ' selected="selected"'; ?>>Dance Team Member</option>
                                <option value="Drama Team Member" <?php if ($row['church_role'] == 'Drama Team Member') echo ' selected="selected"'; ?>>Drama Team Member</option>
                                <option value="Regular Attendee / Member" <?php if ($row['church_role'] == 'Regular Attendee / Member') echo ' selected="selected"'; ?>>Regular Attendee / Member</option>
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
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Select Designation</label>
                        <div class="col-sm-9">
                            <select class="form-select" name="designation" required>
                                <option selected="">Open this select menu</option>
                                <option value="Ministry Coordinator" <?php if ($row['designation'] == 'Ministry Coordinator') echo ' selected="selected"'; ?>>Ministry Coordinator</option>
                                <option value="Media and Tech Coordinator" <?php if ($row['designation'] == 'Media and Tech Coordinator') echo ' selected="selected"'; ?>>Media and Tech Coordinator</option>
                                <option value="Worship Leader" <?php if ($row['designation'] == 'Worship Leader') echo ' selected="selected"'; ?>>Worship Leader</option>
                                <option value="Caregroup Leader" <?php if ($row['designation'] == 'Caregroup Leader') echo ' selected="selected"'; ?>>Caregroup Leader</option>
                                <option value="Student Leader" <?php if ($row['designation'] == 'Student Leader') echo ' selected="selected"'; ?>>Student Leader</option>
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