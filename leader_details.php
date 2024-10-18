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
        <button type="button" class="btn btn-outline-primary m-2" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fas fa-plus me-2"></i>Add New Leader</button>
    </div>

    <div class="row bg-light rounded mx-0">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Leaders Details</h6>
                <div class="table-responsive">
                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th scope="col">Leader ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Church Role</th>
                                <th scope="col">Designation</th>
                                <th scope="col">Church</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM leaders"; 
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $row['id']; ?></th>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['church_role']; ?></td> 
                                    <td><?php echo $row['designation']; ?></td> 
                                    <td><?php echo $row['church']; ?></td> 
                                    <td>
                                        <button type="button" onclick="updateLeader('<?php echo $row['id']; ?>')" class="btn btn-square btn-outline-danger btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" onclick="deleteLeader('<?php echo $row['id']; ?>')" class="btn btn-square btn-outline-success btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Blank End -->



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Leader</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="bg-light rounded h-100 p-4">
                        <form action="api_leader.php?type=add" method="post">
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-3 col-form-label">Leader ID</label>
                                <div class="col-sm-9">
                                    <input type="text" name="id" value="Leader ID Automatic Allocate." readonly class="form-control" id="inputEmail3">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Leader Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" class="form-control" id="inputPassword3" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Select Church Role</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="church_role" id="floatingSelect" aria-label="Floating label select example" required>
                                        <option selected="">Open this select menu</option>
                                        <option value="Pastor">Pastor</option>
                                        <option value="Youth Pastor">Youth Pastor</option>
                                        <option value="Primary Leader">Primary Leader</option>
                                        <option value="Youth Leader">Youth Leader</option>
                                        <option value="Cell Leader">Cell Leader</option>
                                        <option value="Worship Leader">Worship Leader</option>
                                        <option value="Prayer Leader">Prayer Leader</option>
                                        <option value="Choir Member">Choir Member</option>
                                        <option value="Musician">Musician</option>
                                        <option value="Sound Technician">Sound Technician</option>
                                        <option value="Lighting Technician">Lighting Technician</option>
                                        <option value="Media Technician">Media Technician</option>
                                        <option value="Video Operator">Video Operator</option>
                                        <option value="Graphic Designer">Graphic Designer</option>
                                        <option value="Usher">Usher</option>
                                        <option value="Hospitality Team Member">Hospitality Team Member</option>
                                        <option value="Children's Ministry Worker">Children's Ministry Worker</option>
                                        <option value="Creative Arts Director">Creative Arts Director</option>
                                        <option value="Social Media Manager">Social Media Manager</option>
                                        <option value="Website Administrator">Website Administrator</option>
                                        <option value="Dance Team Member">Dance Team Member</option>
                                        <option value="Drama Team Member">Drama Team Member</option>
                                        <option value="Regular Attendee / Member">Regular Attendee / Member</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Church</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="church" id="floatingSelect" aria-label="Floating label select example" required>
                                        <option selected="">Open this select menu</option>
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
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Select Designation</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="designation" id="floatingSelect" aria-label="Floating label select example" required>
                                        <option selected="">Open this select menu</option>
                                        <option value="Ministry Coordinator">Ministry Coordinator</option>
                                        <option value="Media and Tech Coordinator">Media and Tech Coordinator</option>
                                        <option value="Worship Leader">Worship Leader</option>
                                        <option value="Caregroup Leader">Caregroup Leader</option>
                                        <option value="Student Leader">Student Leader</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm-9">
                                    <input type="text" name="password" class="form-control" id="inputPassword3" required>
                                </div>
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


<script>
    function deleteLeader(id) {
        let isDelete = confirm('Are you sure to delete?');
        if (isDelete) {
            window.location = `api_leader.php?type=delete&id=${id}`;
        }
    }

    function updateLeader(id) {
        window.location = `api_leader_update.php?id=${id}`;
    }
</script>

<?php
require('footer.php');
?>