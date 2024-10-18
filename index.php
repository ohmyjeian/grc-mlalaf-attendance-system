<?php
require('header.php');
require "conn.php";
?>

<div class="container pt-3 px-4 m-0">
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-1 rounded-4" style="background: #eee;">
            <li class="breadcrumb-item active" aria-current="page">Home</li>
        </ol>
    </nav>
</div>

<!-- Blank Start -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loop</title>
    <style>
        .typed-text {
            font-family: 'Courier New', Courier, monospace;
            width: 100%;
            margin: 0 auto;
            font-size: 24px;
            overflow-wrap: break-word;
            word-wrap: break-word;    
            white-space: normal;      
        }

        @media (max-width: 600px) {
            .typed-text {
                font-size: 18px; 
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid pt-4 px-4">
        <div class="row vh-100 bg-light rounded align-items-center justify-content-center mx-0">
            <div class="col-md-6 text-center">
                <h3 id="welcomeText"></h3> 

    <script type="module">
      
        window.addEventListener('DOMContentLoaded', () => {
            const text = "Welcome To GRC-MLALAF Attendance System";
            const welcomeText = document.getElementById('welcomeText');

            if (welcomeText) {
                welcomeText.classList.add('typed-text');
                typeWriter(welcomeText, text, 100); 
            }
        });

        function typeWriter(element, text, delay) {
            let index = 0;

            function typeNextCharacter() {
                if (index < text.length) {
                    element.textContent += text.charAt(index);
                    index++;
                    setTimeout(typeNextCharacter, delay);
                } else {
                    
                    setTimeout(() => {
                        element.textContent = ""; 
                        index = 0; 
                        typeNextCharacter(); 
                    }, 1000); 
                }
            }

            typeNextCharacter(); 
        }
    </script>
</body>
</html>

            <?php
            if (isset($_SESSION['leader_id'])) {
                $leader_id = $_SESSION['leader_id'];
                $leader_sql = "SELECT * FROM leaders WHERE `id`=$leader_id";
                $leader_res = mysqli_query($conn, $leader_sql);
                $leader_row = mysqli_fetch_assoc($leader_res);
            ?>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th scope="col">Leader ID</th>
                            <td><?php echo $leader_row['id']; ?></td>
                        </tr>
                        <tr>
                            <th scope="col">Name</th>
                            <td><?php echo $leader_row['name']; ?></td>
                        </tr>
                        <tr>
                            <th scope="col">Church Role</th>
                            <td><?php echo $leader_row['church_role']; ?></td>
                        </tr>
                        <tr>
                            <th scope="col">Designation</th>
                            <td><?php echo $leader_row['designation']; ?></td>
                        </tr>
                        <tr>
                            <th scope="col">Church</th>
                            <td><?php echo $leader_row['church']; ?></td>
                        </tr>
                    </tbody>
                </table>

            <?php
} else if (isset($_SESSION['student_no'])) {
    $student_no = $_SESSION['student_no'];
    // Ensure student_no is treated as a string
    $student_sql = "SELECT * FROM scholars WHERE `student_no`='$student_no'";
    $student_res = mysqli_query($conn, $student_sql);
    
    // Check for query success
    if ($student_res) {
        // Fetch the associative array
        $student_row = mysqli_fetch_assoc($student_res);
        
        // Check if any row is returned
        if ($student_row) {
            ?>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th scope="col">Student No</th>
                        <td><?php echo htmlspecialchars($student_row['student_no']); ?></td>
                    </tr>
                    <tr>
                        <th scope="col">Name</th>
                        <td><?php echo htmlspecialchars($student_row['name']); ?></td>
                    </tr>
                    <tr>
                        <th scope="col">Semester</th>
                        <td><?php echo htmlspecialchars($student_row['semester']); ?></td>
                    </tr>
                    <tr>
                        <th scope="col">Church</th>
                        <td><?php echo htmlspecialchars($student_row['church']); ?></td>
                    </tr>
                    <tr>
                        <th scope="col">Roll No</th>
                        <td><?php echo htmlspecialchars($student_row['roll_no']); ?></td>
                    </tr>
                    <tr>
                        <th scope="col">Year Level</th>
                        <td><?php echo htmlspecialchars($student_row['year_level']); ?></td>
                    </tr>
                </tbody>
            </table>
            <?php
        } else {
            // No rows found
            echo "<p>No student found with Student No: " . htmlspecialchars($student_no) . "</p>";
        }
    } else {
        // Query failed
        echo "Error executing query: " . mysqli_error($conn);
    }
}
?>

        </div>
    </div>
</div>
<!-- Blank End -->

<?php
require('footer.php');
?>