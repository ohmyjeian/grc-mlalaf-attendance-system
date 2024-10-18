<?php
// Include PHPExcel
require 'PHPExcel/PHPExcel.php';
require 'PHPExcel/PHPExcel/IOFactory.php';
require "conn.php";
session_start();

// Check if the user is an admin
if ($_SESSION['usertype'] != 'ADMIN') {
    session_destroy();
    header("location: login.php");
    exit();
}

if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
    // Check if admin_id is set in the session
    if (!isset($_SESSION['admin_id'])) {
        $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
            Admin ID not found in session.
        </div>';
        header("location: login.php"); // Redirect to login or handle accordingly
        exit();
    }

    // Retrieve the admin_id
    $admin_id = $_SESSION['admin_id'];

    // Validate the admin_id exists in the admin table
    $checkAdminQuery = "SELECT * FROM admin WHERE admin_id = '$admin_id'";
    $adminResult = mysqli_query($conn, $checkAdminQuery);

    if (mysqli_num_rows($adminResult) == 0) {
        $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
            Invalid Admin ID. Cannot add scholar.
        </div>';
        header("location: stud_details.php");
        exit();
    }

    // Check if the request type is 'add'
    if (isset($_GET['type']) && $_GET['type'] == "add") {
        $student_no = mysqli_escape_string($conn, $_POST['student_no']);
        $student_name = mysqli_escape_string($conn, $_POST['name']);
        $semester = mysqli_escape_string($conn, $_POST['semester']);
        $church = mysqli_escape_string($conn, $_POST['church']);
        $roll_no = mysqli_escape_string($conn, $_POST['roll_no']);
        $year_level = mysqli_escape_string($conn, $_POST['year_level']);
        $password = mysqli_escape_string($conn, $_POST['password']);

        // Insert into the scholars table, including admin_id
        $sql = "INSERT INTO `scholars`(`student_no`, `name`, `semester`, `church`, `roll_no`, `year_level`, `password`, `admin_id`) 
                VALUES ('$student_no','$student_name','$semester','$church','$roll_no','$year_level','$password', '$admin_id')";
        
        $result = mysqli_query($conn, $sql);
        
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
                Scholar Added.
            </div>';
            header("location: stud_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                Something went wrong! Error: ' . mysqli_error($conn) . '.
            </div>';
            header("location: stud_details.php");
            exit();
        }
    }

    // Check if the request type is 'delete'
    if (isset($_GET['type']) && $_GET['type'] == "delete") {
        $student_no = mysqli_escape_string($conn, $_GET['student_no']);
    
        $sql = "DELETE FROM `scholars` WHERE student_no='$student_no'";
    
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
                Scholar Deleted.
            </div>';
            header("location: stud_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                Something went wrong! Error: ' . mysqli_error($conn) . '.
            </div>';
            header("location: stud_details.php");
            exit();
        }
    }

    // Check if the request type is 'update'
    if (isset($_GET['type']) && $_GET['type'] == "update") {
        $student_no = mysqli_escape_string($conn, $_POST['student_no']);
        $studentname = mysqli_escape_string($conn, $_POST['studentname']);
        $semester = mysqli_escape_string($conn, $_POST['semester']);
        $church = mysqli_escape_string($conn, $_POST['church']);
        $rollno = mysqli_escape_string($conn, $_POST['rollno']);
        $year_level = mysqli_escape_string($conn, $_POST['year_level']);
        $password = mysqli_escape_string($conn, $_POST['password']);

        $sql = "UPDATE `scholars` SET `name`='$studentname', `semester`='$semester', `church`='$church', `roll_no`='$rollno', `year_level`='$year_level', `password`='$password' WHERE `student_no`='$student_no'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
                Student Details Updated.
            </div>';
            header("location: stud_details.php");
            exit();
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                Something went wrong! Error: ' . mysqli_error($conn) . '.
            </div>';
            header("location: stud_details.php");
            exit();
        }
    }

    // Check if the request type is 'bulk' and if a file is uploaded
    if (isset($_GET['type']) && $_GET['type'] == "bulk" && isset($_FILES['file'])) {
        // File upload
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['file']['name']);

        // Check file extension
        $fileExtension = pathinfo($uploadFile, PATHINFO_EXTENSION);
        if (!in_array($fileExtension, ['xls', 'xlsx'])) {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                Invalid file type. Please upload an Excel file.
            </div>';
            header("location: stud_details.php");
            exit();
        }

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            // Expected headers
            $expectedHeaders = ['student_no', 'name', 'semester', 'church', 'roll_no', 'year_level', 'password'];

            try {
                // Load the Excel file
                $objPHPExcel = PHPExcel_IOFactory::load($uploadFile);

                // Get the first worksheet
                $worksheet = $objPHPExcel->getSheet(0);

                // Get the header row
                $headerRow = $worksheet->getRowIterator(1)->current();
                $cellIterator = $headerRow->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $headers = [];
                foreach ($cellIterator as $cell) {
                    $headers[] = trim(strtolower($cell->getValue()));  // Trim and convert to lowercase
                }

                // Validate headers
                if ($headers !== $expectedHeaders) {
                    $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                        Invalid file structure. Please ensure the Excel file has the correct headers in the correct order.
                    </div>';
                    header("location: stud_details.php");
                    exit();
                }

                // Prepare SQL statement
                $stmt = $conn->prepare("INSERT INTO scholars (student_no, name, semester, church, roll_no, year_level, password, admin_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssisisss", $student_no, $name, $semester, $church, $rollno, $year_level, $password, $admin_id);

                // Iterate through rows and columns
                $firstRow = true;
                foreach ($worksheet->getRowIterator() as $row) {
                    if ($firstRow) {
                        $firstRow = false;
                        continue; // Skip the first row (header)
                    }

                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false); // Loop through all cells, even if they are empty

                    $rowData = [];
                    foreach ($cellIterator as $cell) {
                        $rowData[] = $cell ? $cell->getValue() : null; // Check for empty cells
                    }

                    // Debug: Check what data is being read
                    error_log(print_r($rowData, true)); // Log row data for debugging

                    // Ensure rowData has enough elements
                    if (count($rowData) < 7) {
                        $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                            Invalid data in row. Please ensure all fields are filled out correctly.
                        </div>';
                        header("location: stud_details.php");
                        exit();
                    }

                    // Assign values from row data
                    $student_no = $rowData[0]; // student_no
                    $name = $rowData[1]; // name
                    $semester = $rowData[2]; // semester
                    $church = $rowData[3]; // church
                    $rollno = $rowData[4]; // roll_no
                    $year_level = $rowData[5]; // year_level
                    $password = $rowData[6]; // password

                    // Execute prepared statement
                    if (!$stmt->execute()) {
                        $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                            Something went wrong while inserting row: ' . mysqli_error($conn) . '.
                        </div>';
                        header("location: stud_details.php");
                        exit();
                    }
                }

                $_SESSION['msg'] = '<div class="alert alert-success mb-2" role="alert">
                    Scholars Added Successfully from the file.
                </div>';
                header("location: stud_details.php");
                exit();

            } catch (Exception $e) {
                $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                    Error processing file: ' . $e->getMessage() . '.
                </div>';
                header("location: stud_details.php");
                exit();
            }
        } else {
            $_SESSION['msg'] = '<div class="alert alert-danger mb-2" role="alert">
                File could not be uploaded.
            </div>';
            header("location: stud_details.php");
            exit();
        }
    }

} else {
    session_destroy();
    header("location: login.php");
    exit();
}
?>