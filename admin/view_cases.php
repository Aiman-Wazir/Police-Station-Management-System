<?php
include(__DIR__ . '/../includes/db_connect.php');
include(__DIR__ . '/../includes/auth_check.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);



$sql = "SELECT * FROM case_records ORDER BY case_number DESC";
$result = $conn->query($sql);

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/sidebar.php');
?>

<div class="container-fluid">

    <div class="card shadow p-4">

        <h2 class="mb-4">All Cases</h2>

        <table class="table table-bordered table-hover align-middle">

            <thead class="table-dark">

                <tr>
                    <th>Serial #</th>
                    <th>Case Number</th>
                    <th>Name</th>
                    <th>CNIC</th>
                    <th>Police Station</th>
                    <th width="250">Actions</th>
                </tr>

            </thead>

            <tbody>

            <?php while($row = $result->fetch_assoc()) { ?>

                <tr>

                    <td><?php echo $row['serial_number']; ?></td>

                    <td><?php echo $row['case_number']; ?></td>

                    <td><?php echo $row['person_name']; ?></td>

                    <td><?php echo $row['cnic']; ?></td>

                    <td><?php echo $row['police_station']; ?></td>

                    <td>

                        <a href="view_case.php?case_number=<?php echo urlencode($row['case_number']); ?>"
                           class="btn btn-primary btn-sm">
                            View
                        </a>

                        <a href="edit_case.php?case_number=<?php echo urlencode($row['case_number']); ?>"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <a href="delete.php?case_number=<?php echo urlencode($row['case_number']); ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this case?');">
                            Delete
                        </a>

                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<?php
include(__DIR__ . '/../includes/footer.php');
?>