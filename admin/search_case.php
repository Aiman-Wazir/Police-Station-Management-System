<?php

include(__DIR__ . '/../includes/db_connect.php');
include(__DIR__ . '/../includes/auth_check.php');
include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/sidebar.php');

if(!isset($_GET['search'])){

    die("Please enter a search term.");

}

$search = trim($_GET['search']);

$stmt = $conn->prepare("
SELECT *
FROM case_records
WHERE case_number LIKE ?
OR person_name LIKE ?
OR cnic LIKE ?
ORDER BY created_at DESC
");

$keyword = "%".$search."%";

$stmt->bind_param(
    "sss",
    $keyword,
    $keyword,
    $keyword
);

$stmt->execute();

$result = $stmt->get_result();

?>

<div class="container-fluid">

<div class="card shadow p-4">

<h2>Search Results</h2>

<p>
Search:
<b><?php echo htmlspecialchars($search); ?></b>
</p>

<table class="table table-bordered table-hover">

<tr>

<th>Case Number</th>

<th>Name</th>

<th>CNIC</th>

<th>Police Station</th>

<th>Action</th>

</tr>

<?php

if($result->num_rows>0){

while($row=$result->fetch_assoc()){

?>

<tr>

<td><?php echo $row['case_number']; ?></td>

<td><?php echo $row['person_name']; ?></td>

<td><?php echo $row['cnic']; ?></td>

<td><?php echo $row['police_station']; ?></td>

<td>

<a
href="view_case.php?case_number=<?php echo urlencode($row['case_number']); ?>"
class="btn btn-primary btn-sm">

View

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="5" class="text-center">

No matching case found.

</td>

</tr>

<?php

}

?>

</table>

<a href="dashboard.php" class="btn btn-secondary">

Back to Dashboard

</a>

</div>

</div>

<?php

include(__DIR__ . '/../includes/footer.php');

?>