<?php
include("config/db.php");

$message = "";

if(isset($_POST['register']))
{
    $fullname = mysqli_real_escape_string($conn,$_POST['fullname']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check passwords

    if($password != $confirm_password)
    {
        $message = "Passwords do not match!";
    }
    else
    {
        // Check email exists

        $check = mysqli_query(
            $conn,
            "SELECT * FROM users WHERE email='$email'"
        );

        if(mysqli_num_rows($check) > 0)
        {
            $message = "Email already registered!";
        }
        else
        {
            $hashed_password =
                password_hash($password, PASSWORD_DEFAULT);

            $insert = mysqli_query(
                $conn,
                "INSERT INTO users(fullname,email,password)
                 VALUES('$fullname','$email','$hashed_password')"
            );

            if($insert)
            {
                header("Location: login.php");
                exit();
            }
            else
            {
                $message = "Registration Failed!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Register | Diagnostic Centre</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet">

<style>

body{
    font-family:'Poppins',sans-serif;
    background:#f8f9fa;
}

.register-box{
    max-width:500px;
    margin:auto;
}

</style>

</head>

<body>

<div class="container py-5">

<div class="register-box">

<div class="card shadow">

<div class="card-body p-4">

<h2 class="text-center text-primary mb-4">
Create Account
</h2>

<?php
if($message!="")
{
    echo "<div class='alert alert-danger'>$message</div>";
}
?>

<form method="POST">

<div class="mb-3">

<label class="form-label">
Full Name
</label>

<input type="text"
       name="fullname"
       class="form-control"
       required>

</div>

<div class="mb-3">

<label class="form-label">
Email Address
</label>

<input type="email"
       name="email"
       class="form-control"
       required>

</div>

<div class="mb-3">

<label class="form-label">
Password
</label>

<input type="password"
       name="password"
       class="form-control"
       required>

</div>

<div class="mb-3">

<label class="form-label">
Confirm Password
</label>

<input type="password"
       name="confirm_password"
       class="form-control"
       required>

</div>

<button type="submit"
        name="register"
        class="btn btn-primary w-100">

Register

</button>

<div class="text-center mt-3">

Already have an account?

<a href="login.php">
Login
</a>

</div>

</form>

</div>

</div>

</div>

</div>

</body>
</html>