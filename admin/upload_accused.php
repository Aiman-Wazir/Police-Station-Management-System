<?php
include(__DIR__ . '/../includes/db_connect.php');
include(__DIR__ . '/../includes/auth_check.php');
error_reporting(E_ALL);
ini_set('display_errors',1);



if(!isset($_GET['case_number'])){
    die("Invalid Request");
}

$case_number = $_GET['case_number'];

/* Get the serial number using the case number */
$stmt = $conn->prepare("SELECT serial_number FROM case_records WHERE case_number=?");
$stmt->bind_param("s", $case_number);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows == 0){
    die("Case Not Found");
}

$case = $result->fetch_assoc();
$serial_number = $case['serial_number'];

if(isset($_POST['upload'])){

    if($_FILES['photo']['error'] != 0){
        die("Please select an image.");
    }

    $filename = time()."_".basename($_FILES['photo']['name']);

    $target = "../uploads/".$filename;

    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    $allowed = ['jpg','jpeg','png'];

    if(!in_array($extension,$allowed)){
        die("Only JPG, JPEG and PNG files are allowed.");
    }

    if(move_uploaded_file($_FILES['photo']['tmp_name'],$target)){

        $stmt = $conn->prepare("
            INSERT INTO accused_photos
            (case_id, photo_path, uploaded_at)
            VALUES (?, ?, NOW())
        ");

        $stmt->bind_param("is",$serial_number,$filename);

        if($stmt->execute()){

            echo "<script>
                    alert('Accused Photo Uploaded Successfully');
                    window.location='view_case.php?case_number=$case_number';
                  </script>";
            exit();

        }else{

            echo "Database Error : ".$stmt->error;

        }

    }else{

        echo "Failed to upload image.";

    }

}

/* Layout */
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/sidebar.php');
?>

<div class="container-fluid">

    <div class="card shadow p-4">

        <h2 class="mb-4">
            Upload Accused Photo
        </h2>

        <p>
            <strong>Case Number:</strong>
            <?php echo $case_number; ?>
        </p>

        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">

                <label class="form-label">
                    Select Photo
                </label>

                <input
                    type="file"
                    name="photo"
                    class="form-control"
                    accept="image/*"
                    required>

            </div>

            <div class="mt-4">

                <button
                    type="submit"
                    name="upload"
                    class="btn btn-primary">

                    Upload Photo

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