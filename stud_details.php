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
            <li class="breadcrumb-item">Scholars</li>
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
        <button type="button" class="btn btn-outline-primary m-2" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fas fa-plus me-2"></i>Add New Scholar</button>
        <button type="button" class="btn btn-outline-success m-2" data-bs-toggle="modal" data-bs-target="#excelstudModal"><i class="fas fa-plus me-2"></i>Add Bulk Scholar</button>
    </div>

    <div class="row bg-light rounded mx-0">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Scholars Details</h6>
                <div class="table-responsive">
                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th scope="col">Student No</th>
                                <th scope="col">Name</th>
                                <th scope="col">Semester</th>
                                <th scope="col">Church</th>
                                <th scope="col">Roll No</th>
                                <th scope="col">Year Level</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM scholars";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $row['student_no']; ?></th> 
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['semester']; ?></td>
                                    <td><?php echo $row['church']; ?></td>
                                    <td><?php echo $row['roll_no']; ?></td>
                                    <td><?php echo $row['year_level']; ?></td>
                                    <td>
                                        <button type="button" onclick="updatestud('<?php echo $row['student_no']; ?>')" class="btn btn-square btn-outline-danger btn-sm"><i class="fas fa-edit"></i></button>
                                        <button type="button" onclick="deletestud('<?php echo $row['student_no']; ?>')" class="btn btn-square btn-outline-success btn-sm"><i class="fas fa-trash"></i></button>
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
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Scholar</h1> 
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="bg-light rounded h-100 p-4">
                        <form action="api_stud.php?type=add" method="post">
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-sm-3 col-form-label">Student No</label>
                                <div class="col-sm-9">
                                    <input type="text" name="student_no" class="form-control" id="inputEmail3"> 
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Scholar Name</label> 
                                <div class="col-sm-9">
                                    <input type="text" name="name" class="form-control" id="inputPassword3"> 
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Select Semester</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="semester" id="floatingSelect" aria-label="Floating label select example" required>
                                        <option selected="">Open this select menu</option>
                                        <option value="1">First</option>
                                        <option value="2">Second</option>
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
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Assign Roll No</label>
                                <div class="col-sm-9">
                                    <input type="text" name="roll_no" class="form-control" id="inputPassword3"> 
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Select Year Level</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="year_level" id="floatingSelect" aria-label="Floating label select example" required> 
                                        <option selected="">Open this select menu</option>
                                        <option value="1">First</option>
                                        <option value="2">Second</option>
                                        <option value="3">Third</option>
                                        <option value="4">Fourth</option> 
                                        <option value="5">Fifth</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword3" class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm-9">
                                    <input type="text" name="password" class="form-control" id="inputPassword3">
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



<!-- Modal -->
<div class="modal fade" id="excelstudModal" tabindex="-1" aria-labelledby="excelstudModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="excelstudModalLabel">Add Bulk Scholar</h1> 
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <img src="./img/excel.png" alt="" class="img-fluid">
                    <p class="text-danger mt-3">*Note: Please ensure that the Excel file is organized in the same order as the image shown above.</p>
                    <div class="bg-light rounded h-100 p-4">
                        <form action="api_stud.php?type=bulk" method="post" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <label for="inputEmail3" class="col-form-label">Select Excel File</label>
                                <input type="file" name="file" class="form-control" id="inputEmail3" accept=".xls,.xlsx" required>
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
    function deletestud(student_no) { 
        let isDelete = confirm('Are you sure to delete?');
        if (isDelete) {
            window.location = `api_stud.php?type=delete&student_no=${student_no}`; 
        }
    }

    function updatestud(student_no) { 
        window.location = `api_stud_update.php?student_no=${student_no}`;
    }
</script>

<?php
require('footer.php');
?>
