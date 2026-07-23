
 <!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Police Station Management System</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        body{
            margin:0;
            padding:0;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:linear-gradient(135deg,#0b3d91,#1c6dd0);
            font-family:Arial,Helvetica,sans-serif;
        }

        .login-card{

            width:420px;
            background:#fff;
            border-radius:15px;
            padding:40px;
            box-shadow:0 10px 30px rgba(0,0,0,.3);

        }

        .logo{

            text-align:center;
            font-size:60px;
            color:#0d6efd;
            margin-bottom:10px;

        }

        h2{

            text-align:center;
            margin-bottom:5px;
            color:#0d6efd;

        }

        .subtitle{

            text-align:center;
            color:#666;
            margin-bottom:30px;

        }

        .form-control{

            height:48px;

        }

        .btn-login{

            width:100%;
            height:48px;
            font-size:18px;
            font-weight:bold;

        }

        .footer{

            text-align:center;
            margin-top:25px;
            color:#777;
            font-size:14px;

        }

    </style>

</head>

<body>

<div class="login-card">

    <div class="logo">
        <i class="bi bi-shield-lock-fill"></i>
    </div>

    <h2>Police Station</h2>

    <p class="subtitle">
        Management System
    </p>

    <form action="login_process.php" method="POST">

        <div class="mb-3">

            <label class="form-label">
                Username
            </label>

            <input
                type="text"
                name="username"
                class="form-control"
                placeholder="Enter Username"
                required>

        </div>

        <div class="mb-4">

            <label class="form-label">
                Password
            </label>

            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="Enter Password"
                required>

        </div>

        <button
            type="submit"
            class="btn btn-primary btn-login">

            <i class="bi bi-box-arrow-in-right"></i>
            Login

        </button>

    </form>

    <div class="footer">

        © <?php echo date("Y"); ?> Police Station Management System

    </div>

</div>

</body>

</html>