
<?php
include(__DIR__ . '/../includes/db_connect.php');
include(__DIR__ . '/../includes/auth_check.php');
error_reporting(E_ALL);
ini_set('display_errors',1);



if(!isset($_GET['case_number'])){
    die("Invalid Request");
}

$case_number = $_GET['case_number'];

/* Get serial number */
$stmt = $conn->prepare("SELECT serial_number FROM case_records WHERE case_number=?");
$stmt->bind_param("s",$case_number);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows == 0){
    die("Case Not Found");
}

$case = $result->fetch_assoc();
$serial_number = $case['serial_number'];

if(isset($_POST['upload'])){

    $description = $_POST['description'];

    if($_FILES['evidence']['error'] != 0){
        die("Please select an image.");
    }

    $filename = time()."_".basename($_FILES['evidence']['name']);

    $target = "../uploads/".$filename;

    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    $allowed = ['jpg','jpeg','png'];

    if(!in_array($extension,$allowed)){
        die("Only JPG, JPEG and PNG files are allowed.");
    }

    if(move_uploaded_file($_FILES['evidence']['tmp_name'],$target)){

        $stmt = $conn->prepare("
            INSERT INTO evidence_images
            (case_id,image_path,description,uploaded_at)
            VALUES(?,?,?,NOW())
        ");

        $stmt->bind_param(
            "iss",
            $serial_number,
            $filename,
            $description
        );

        if($stmt->execute()){

            echo "<script>
                    alert('Evidence uploaded successfully');
                    window.location='view_case.php?case_number=$case_number';
                  </script>";

            exit();

        }else{

            echo $stmt->error;

        }

    }else{

        echo "Failed to upload image.";

    }

}

/* Header & Sidebar */
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/sidebar.php');
?>

<div class="container-fluid">

    <div class="card shadow p-4">

        <h2 class="mb-4">
            Upload Evidence
        </h2>

        <p>
            <strong>Case Number:</strong>
            <?php echo $case_number; ?>
        </p>

        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">

                <label class="form-label">
                    Select Evidence Image
                </label>

                <input
                    type="file"
                    name="evidence"
                    class="form-control"
                    accept="image/*"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Description
                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="5"
                    placeholder="Enter evidence description..."
                    required></textarea>

            </div>

            <div class="mt-4">

                <button
                    type="submit"
                    name="upload"
                    class="btn btn-success">

                    Upload Evidence

                </button>

                <a
                    href="view_case.php?case_number=<?php echo $case_number; ?>"
                    class="btn btn-secondary">

                    Back

                </a>

            </div>

        </form>

    </div>

</div>

<?php
include(__DIR__ . '/../includes/footer.php');
?>