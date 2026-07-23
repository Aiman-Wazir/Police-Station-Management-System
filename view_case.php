<?php
include(__DIR__ . '/../includes/db_connect.php');
include(__DIR__ . '/../includes/auth_check.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);



/* Fetch Case Number */
if (!isset($_GET['case_number'])) {
    die("Invalid Request");
}

$case_number = $_GET['case_number'];

/* Fetch Case Details */
$stmt = $conn->prepare("SELECT * FROM case_records WHERE case_number=?");
$stmt->bind_param("s", $case_number);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Case not found");
}

$case = $result->fetch_assoc();

/* Get Serial Number */
$serial_number = $case['serial_number'];

/* Fetch Accused Photo */
$stmt = $conn->prepare("SELECT * FROM accused_photos WHERE case_id=?");
$stmt->bind_param("i", $serial_number);
$stmt->execute();

$accused_result = $stmt->get_result();

/* Fetch Evidence Images */
$stmt = $conn->prepare("SELECT * FROM evidence_images WHERE case_id=?");
$stmt->bind_param("i", $serial_number);
$stmt->execute();

$evidence_result = $stmt->get_result();

/* Header & Sidebar */
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/sidebar.php');
?>

<div class="container-fluid">

    <div class="card shadow p-4">

        <h2 class="mb-4">Case Details</h2>

        <div class="mb-4">

            <a href="upload_accused.php?case_number=<?php echo $case['case_number']; ?>"
               class="btn btn-primary">
                Upload Accused Photo
            </a>

            <a href="upload_evidence.php?case_number=<?php echo $case['case_number']; ?>"
               class="btn btn-success">
                Upload Evidence
            </a>

            <a href="view_cases.php"
               class="btn btn-secondary">
                Back
            </a>

        </div>

        <table class="table table-bordered">

            <tr>
                <th width="250">Case Number</th>
                <td><?php echo $case['case_number']; ?></td>
            </tr>

            <tr>
                <th>Name</th>
                <td><?php echo $case['person_name']; ?></td>
            </tr>

            <tr>
                <th>CNIC</th>
                <td><?php echo $case['cnic']; ?></td>
            </tr>

            <tr>
                <th>Contact Number</th>
                <td><?php echo $case['contact_number']; ?></td>
            </tr>

            <tr>
                <th>Relative Name</th>
                <td><?php echo $case['relative_name']; ?></td>
            </tr>

            <tr>
                <th>Police Station</th>
                <td><?php echo $case['police_station']; ?></td>
            </tr>

            <tr>
                <th>Legal Section</th>
                <td><?php echo $case['legal_section']; ?></td>
            </tr>

            <tr>
                <th>Challan</th>
                <td><?php echo $case['challan']; ?></td>
            </tr>

            <tr>
                <th>Remarks</th>
                <td><?php echo $case['remarks']; ?></td>
            </tr>

            <tr>
                <th>Created At</th>
                <td><?php echo $case['created_at']; ?></td>
            </tr>

        </table>

        <hr>

        <h3>Accused Photo</h3>

        <?php
        if($accused_result->num_rows > 0){

            $photo = $accused_result->fetch_assoc();
        ?>

            <img src="../uploads/<?php echo $photo['photo_path']; ?>"
                 class="img-thumbnail"
                 width="300">

        <?php
        }else{
            echo "<p>No accused photo uploaded.</p>";
        }
        ?>

        <hr>

        <h3>Evidence Images</h3>

        <div class="row">

        <?php

        if($evidence_result->num_rows > 0){

            while($evidence = $evidence_result->fetch_assoc()){

        ?>

            <div class="col-md-4 mb-4">

                <div class="card">

                    <img src="../uploads/<?php echo $evidence['image_path']; ?>"
                         class="card-img-top"
                         height="250">

                    <div class="card-body">

                        <h5>Description</h5>

                        <p>
                            <?php echo htmlspecialchars($evidence['description']); ?>
                        </p>

                        <small class="text-muted">

                            Uploaded:
                            <?php echo $evidence['uploaded_at']; ?>

                        </small>

                    </div>

                </div>

            </div>

        <?php

            }

        }else{

            echo "<div class='col-12'>";
            echo "<p>No evidence uploaded.</p>";
            echo "</div>";

        }

        ?>

        </div>

    </div>

</div>

<?php
include(__DIR__ . '/../includes/footer.php');
?>