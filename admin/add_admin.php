<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include(__DIR__ . '/../includes/db_connect.php');

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO admins(username,password) VALUES(?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss",$username,$password);

    if($stmt->execute()){

        echo "<script>
                alert('Admin Added Successfully');
                window.location='dashboard.php';
              </script>";

        exit();

    }else{

        echo "<script>
                alert('".$stmt->error."');
              </script>";
    }

}

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../includes/sidebar.php');
?>

<style>

.admin-container{
    max-width:650px;
    margin:20px auto;
}

.admin-card{
    background:#fff;
    border-radius:12px;
    padding:35px;
    box-shadow:0 5px 15px rgba(0,0,0,.15);
}

.admin-card h2{
    text-align:center;
    color:#0d6efd;
    margin-bottom:30px;
}

.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    font-weight:bold;
    margin-bottom:8px;
}

.form-group input{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:6px;
    font-size:16px;
}

.buttons{
    margin-top:25px;
}

.buttons .btn{
    margin-right:10px;
}

</style>

<div class="admin-container">

    <div class="admin-card">

        <h2>Add New Admin</h2>

        <form method="POST">

            <div class="form-group">

                <label>Username</label>

                <input
                    type="text"
                    name="username"
                    placeholder="Enter Username"
                    required>

            </div>

            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter Password"
                    required>

            </div>

            <div class="buttons">

                <button
                    type="submit"
                    class="btn btn-primary">

                    Add Admin

                </button>

                <a
                    href="dashboard.php"
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