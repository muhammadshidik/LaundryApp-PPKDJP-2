<?php
session_start();
require_once 'admin/controller/koneksi.php';
require_once 'admin/controller/functions.php';

if (isset($_POST['login'])) {
  $email    = $_POST['email'];
  $password = $_POST['password'];

  $queryLogin = mysqli_query($connection, "SELECT * FROM user WHERE email='$email' AND password='$password'");

  if (mysqli_num_rows($queryLogin) > 0) {
    $rowLogin = mysqli_fetch_assoc($queryLogin);
    if ($password == $rowLogin['password']) {
      $_SESSION['id']   = $rowLogin['id'];
      $_SESSION['name'] = $rowLogin['name'];
      header("location:menu.php");
      exit;
    } else {
      header("location:login.php?login=failed");
      exit;
    }
  } else {
    header("location:login.php?login=failed");
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | Laundry App</title>
  <link rel="shortcut icon" href="tmp/assets/images/logos/favicon.png" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
 <style>
  body {
    margin: 0;
    padding: 0;
    height: 100vh;
    background: linear-gradient(-45deg, #ff9a9e, #fad0c4, #a1c4fd, #c2e9fb);
    background-size: 400% 400%;
    animation: gradientBG 15s ease infinite;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Segoe UI', sans-serif;
  }

  @keyframes gradientBG {
    0% {
      background-position: 0% 50%;
    }
    50% {
      background-position: 100% 50%;
    }
    100% {
      background-position: 0% 50%;
    }
  }

  .login-card {
    max-width: 450px;
    width: 100%;
    background-color: rgba(255, 255, 255, 0.95);
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    padding: 2rem;
    backdrop-filter: blur(5px);
  }
</style>

</head>

<body>
  <div class="login-card">
  <div class="text-center py-4">
  <img src="http://yourdomain.com/path-to-image/logo.svg" alt="Laundry Logo" class="mb-3" style="max-width: 120px;">
  <h3 class="fw-semibold text-primary">Login</h3>
  <p class="text-muted fs-6">Silakan login untuk mengakses Dashboard Laundry Anda</p>
</div>


    <?php if (isset($_GET['login']) && $_GET['login'] === 'failed'): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        Email atau Password salah.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['register']) && $_GET['register'] === 'success'): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        Akun berhasil didaftarkan, silakan login.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input
          type="email"
          name="email"
          class="form-control"
          id="email"
          placeholder="Enter your email"
          required
        >
      </div>
      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input
          type="password"
          name="password"
          class="form-control"
          id="password"
          placeholder="Enter your password"
          required
        >
      </div>
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="rememberMe" checked>
          <label class="form-check-label" for="rememberMe">Remember Me</label>
        </div>
        <a href="#" class="text-primary text-decoration-none">Forgot Password?</a>
      </div>
      <button type="submit" name="login" class="btn btn-success w-100">Login</button>
    </form>
  </div>

  <div class="footer-text">
    &copy; <?= date('Y') ?> <strong>Muhammad Siddiq</strong>. All rights reserved.
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
