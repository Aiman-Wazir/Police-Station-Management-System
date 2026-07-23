<?php
include(__DIR__ . '/../includes/db_connect.php');
include(__DIR__ . '/../includes/auth_check.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (isset($_POST['add_case'])) {

    $person_name = $_POST['person_name'];
    $cnic = $_POST['cnic'];
    $contact_number = $_POST['contact_number'];
    $relative_name = $_POST['relative_name'];
    $police_station = $_POST['police_station'];
    $legal_section = $_POST['legal_section'];
    $challan = $_POST['challan'];
    $remarks = $_POST['remarks'];

    $case_number = "CASE-" . time();

    $stmt = $conn->prepare("
        INSERT INTO case_records
        (case_number, person_name, cnic, contact_number,
        relative_name, police_station, legal_section,
        challan, remarks, created_at)
        VALUES(?,?,?,?,?,?,?,?,?,NOW())
    ");

    $stmt->bind_param(
        "sssssssss",
        $case_number,
        $person_name,
        $cnic,
        $contact_number,
        $relative_name,
        $police_station,
        $legal_section,
        $challan,
        $remarks
    );

    if($stmt->execute()){

        $serial_number = $conn->insert_id;

        // Upload Accused Photo
        if(!empty($_FILES['accused_photo']['name'])){

            $filename = time()."_".basename($_FILES['accused_photo']['name']);

            move_uploaded_file(
                $_FILES['accused_photo']['tmp_name'],
                "../uploads/".$filename
            );

            $stmt2 = $conn->prepare("
                INSERT INTO accused_photos
                (case_id, photo_path, uploaded_at)
                VALUES(?,?,NOW())
            ");

            $stmt2->bind_param("is",$serial_number,$filename);
            $stmt2->execute();
        }

        // Upload Evidence Images
        if(!empty($_FILES['evidence_images']['name'][0])){

            foreach($_FILES['evidence_images']['tmp_name'] as $key=>$tmpName){

                $imageName = time()."_".$_FILES['evidence_images']['name'][$key];

                move_uploaded_file(
                    $tmpName,
                    "../uploads/".$imageName
                );

                $description = "Evidence";

                $stmt3 = $conn->prepare("
                    INSERT INTO evidence_images
                    (case_id,image_path,description,uploaded_at)
                    VALUES(?,?,?,NOW())
                ");

                $stmt3->bind_param(
                    "iss",
                    $serial_number,
                    $imageName,
                    $description
                );

                $stmt3->execute();
            }
        }

        echo "<script>
                alert('Case Added Successfully');
                window.location='view_cases.php';
              </script>";
    }
    else{
        echo "Error : ".$stmt->error;
    }
}

/* Layout */
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/sidebar.php');
?>

<div class="container-fluid">

    <div class="card shadow p-4">

        <h2 class="mb-4">Add New Case</h2>

        <form method="POST" enctype="multipart/form-data">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Person Name</label>
                    <input type="text"
                           name="person_name"
                           class="form-control"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">CNIC</label>
                    <input type="text"
                           name="cnic"
                           class="form-control"
                           required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="text"
                           name="contact_number"
                           class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Relative Name</label>
                    <input type="text"
                           name="relative_name"
                           class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Police Station</label>
                    <input type="text"
                           name="police_station"
                           class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Legal Section</label>
                    <input type="text"
                           name="legal_section"
                           class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Challan</label>
                    <input type="text"
                           name="challan"
                           class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Remarks</label>
                    <input type="text"
                           name="remarks"
                           class="form-control">
                </div>

            </div>

            <hr>

            <h4>Accused Photo</h4>

            <div class="mb-3">

                <input type="file"
                       name="accused_photo"
                       class="form-control"
                       accept="image/*">

            </div>

            <hr>

            <h4>Evidence Images</h4>

            <div class="mb-3">

                <input type="file"
                       name="evidence_images[]"
                       class="form-control"
                       multiple
                       accept="image/*">

            </div>

            <div class="text-end">

                <button type="submit"
                        name="add_case"
                        class="btn btn-primary">

                    Add Case

                </button>

            </div>

        </form>

    </div>

</div>

<?php
include(__DIR__ . '/../includes/footer.php');
?>