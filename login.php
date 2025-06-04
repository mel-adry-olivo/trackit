<?php
include "database/database.php";

session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $role = $_POST['role'] ?? '';
  $idNumber = $_POST['idNumber'] ?? '';
  $password = $_POST['password'] ?? '';

  if (!$role) {
    $errors[] = 'Please select a user type.';
  }
  if (!$idNumber) {
    $errors[] = 'Please enter your ID number.';
  }
  if (!$password) {
    $errors[] = 'Please enter your password.';
  }

  if (empty($errors)) {
    $conn = connect_to_database();
    if (!$conn) {
      $errors[] = 'Database connection failed: ' . mysqli_connect_error();
    } else {
      $role_esc = mysqli_real_escape_string($conn, $role);
      $idNumber_esc = (int) $idNumber;

      $query = "SELECT * FROM users WHERE uid = $idNumber_esc AND role = '$role_esc' LIMIT 1";
      $result = mysqli_query($conn, $query);

      if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
          $_SESSION['uid'] = $user['uid'];
          $_SESSION['username'] = $user['username'];
          $_SESSION['role'] = $user['role'];
          $_SESSION['firstname'] = $user['firstname'];
          $_SESSION['lastname'] = $user['lastname'];
          $_SESSION['full_name'] = $user['full_name'];
          $_SESSION['date_of_birth'] = $user['date_of_birth'];
          $_SESSION['place_of_birth'] = $user['place_of_birth'];
          $_SESSION['gender'] = $user['gender'];
          $_SESSION['civil_status'] = $user['civil_status'];
          $_SESSION['nationality'] = $user['nationality'];
          $_SESSION['phone'] = $user['phone'];
          $_SESSION['email'] = $user['email'];
          $_SESSION['address'] = $user['address'];
          $_SESSION['start_date'] = $user['start_date'];
          $_SESSION['end_date'] = $user['end_date'];
          $_SESSION['job_title'] = $user['job_title'];
          $_SESSION['created_at'] = $user['created_at'];
          $_SESSION['emergency_contact_name'] = $user['emergency_contact_name'];
          $_SESSION['emergency_relationship'] = $user['emergency_relationship'];
          $_SESSION['emergency_phone'] = $user['emergency_phone'];
          $_SESSION['profile_image'] = $user['profile_image'];  // new

          mysqli_free_result($result);
          mysqli_close($conn);

          // Redirect based on role
          switch ($user['role']) {
            case 'manager':
              header('Location: ./manager_home.php');
              exit;
            case 'employee':
              header('Location: ./emp-db.php');
              exit;
            case 'admin': // if admin exists
              header('Location: ./admin/index.php');
              exit;
            default:
              $errors[] = 'Invalid user role.';
          }
        } else {
          $errors[] = 'Incorrect password.';
        }
      } else {
        $errors[] = 'User not found with provided ID and role.';
      }

      if ($result) {
        mysqli_free_result($result);
      }
      mysqli_close($conn);
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log in</title>
  <link rel="stylesheet" href="assets/css/styles.css" type="text/css">
</head>

<body>
  <nav class="navbar" id="landing">
    <div class="logo"><a href="index.php" class="home">TrackIT</a></div>
    <div class="nav-buttons">
      <a href="personal.php" id="employee" class="active">Personal</a>
      <a href="business.php" id="manager">Business</a>
    </div>
  </nav>
  <div class="login-container">
    <div class="left-panel">
      <img id="roleImage" src="assets/img/admin.jpg" alt="Role Image" class="fade-in" />
    </div>
    <div class="right-panel">
      <h2>Login to <br><span>Your Account</span></h2>
      <?php if (!empty($errors)): ?>
        <div class="error-messages" style="color: red; margin-bottom: 1em;">
          <ul>
            <?php foreach ($errors as $error): ?>
              <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
      <form id="loginForm" method="POST" action="">
        <label for="role">User Type</label>
        <select id="role" name="role" required>
          <option value="" disabled <?= empty($role) ? 'selected' : '' ?>>Select User Type</option>
          <option value="manager" <?= (isset($role) && $role === 'manager') ? 'selected' : '' ?>>Manager</option>
          <option value="employee" <?= (isset($role) && $role === 'employee') ? 'selected' : '' ?>>Employee</option>
          <option value="admin" <?= (isset($role) && $role === 'admin') ? 'selected' : '' ?>>Admin</option>
        </select>

        <label for="idNumber">ID Number</label>
        <input type="text" id="idNumber" name="idNumber" placeholder="Enter your ID number"
          value="<?= isset($idNumber) ? htmlspecialchars($idNumber) : '' ?>" required />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required />

        <button type="submit">Login</button>
      </form>

      <p class="footer-text">
        By logging in, you agree to the System's <a href="privacy.php">Terms of Service and Privacy Policy.</a>
      </p>
    </div>
  </div>
  </div>
  <!--End-->


  <script>
    const roleSelect = document.getElementById('role');
    const roleImage = document.getElementById('roleImage');

    const roleImages = {
      admin: 'assets/img/admin.jpg',
      manager: 'assets/img/manager.jpg',
      employee: 'assets/img/employee.jpg'
    };

    roleSelect.addEventListener('change', () => {
      const selectedRole = roleSelect.value;

      if (roleImages[selectedRole]) {

        roleImage.classList.add('fade-out');

        setTimeout(() => {
          roleImage.src = roleImages[selectedRole];


          roleImage.onload = () => {
            roleImage.classList.remove('fade-out');
          };
        }, 300);
      }
    });
  </script>
</body>

</html>