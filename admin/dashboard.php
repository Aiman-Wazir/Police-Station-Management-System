<?php
include '../includes/auth_check.php';
include(__DIR__ . '/../includes/db_connect.php');

// Dashboard Queries

$total_cases = $conn->query("
    SELECT COUNT(*) AS total
    FROM case_records
")->fetch_assoc()['total'];

$active_cases = $conn->query("
    SELECT COUNT(*) AS total
    FROM case_records
    WHERE status='active'
")->fetch_assoc()['total'];

$closed_cases = $conn->query("
    SELECT COUNT(*) AS total
    FROM case_records
    WHERE status='closed'
")->fetch_assoc()['total'];

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/sidebar.php');
?>

<div class="container-fluid">

    <h2 class="mb-4">
        Dashboard
    </h2>
    <div class="card shadow p-4 mb-4">

    <h4>🔍 Search Case</h4>

    <form action="search_case.php" method="GET">

        <div class="row">

            <div class="col-md-10">

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search by Case Number, Name or CNIC"
                    required>

            </div>

            <div class="col-md-2">

                <button
                    type="submit"
                    class="btn btn-primary w-100">

                    Search

                </button>

            </div>

        </div>

    </form>

</div>

    <div class="row">

        <div class="col-md-4">

            <div class="card p-4 text-center">

                <h4>Total Cases</h4>

                <h1><?php echo $total_cases; ?></h1>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card p-4 text-center">

                <h4>Active Cases</h4>

                <h1><?php echo $active_cases; ?></h1>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card p-4 text-center">

                <h4>Closed Cases</h4>

                <h1><?php echo $closed_cases; ?></h1>

            </div>

        </div>

    </div>

</div>

<?php
include(__DIR__ . '/../includes/footer.php');
?>