<?php

include 'database/database.php';

session_start();

$userCode = $_SESSION['user_code'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_field'])) {
  if (!$userCode)
    die('Unauthorized');

  $conn = connect_to_database();
  if (!$conn)
    die('DB connection failed');

  $allowedFields = ['civil_status', 'nationality', 'phone', 'email', 'address', 'emergency_contact_name', 'emergency_phone', 'emergency_relationship'];
  $field = $_POST['field'];

  if (in_array($field, $allowedFields)) {
    $value = mysqli_real_escape_string($conn, $_POST[$field] ?? '');

    if ($field === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['error_message'] = "Invalid email.";
    } else {
      $sql = "UPDATE users SET `$field` = '$value' WHERE user_code = '$userCode'";
      if (mysqli_query($conn, $sql)) {
        $_SESSION[$field] = $value;
        $_SESSION['success_message'] = ucfirst(str_replace('_', ' ', $field)) . " updated successfully.";
      } else {
        $_SESSION['error_message'] = "Update failed: " . mysqli_error($conn);
      }
    }
  } else {
    $_SESSION['error_message'] = "Invalid field.";
  }

  mysqli_close($conn);
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
  if (!$userCode)
    die('Unauthorized');

  $conn = connect_to_database();
  if (!$conn)
    die('DB connection failed');

  $currentPassword = $_POST['current_password'] ?? '';
  $newPassword = $_POST['new_password'] ?? '';
  $confirmPassword = $_POST['confirm_password'] ?? '';

  // Get current password hash from DB
  $query = "SELECT password FROM users WHERE user_code = '$userCode'";
  $result = mysqli_query($conn, $query);
  $user = mysqli_fetch_assoc($result);

  if (!$user || !password_verify($currentPassword, $user['password'])) {
    $_SESSION['error_message'] = "Current password is incorrect.";
  } elseif ($newPassword !== $confirmPassword) {
    $_SESSION['error_message'] = "New passwords do not match.";
  } elseif (strlen($newPassword) < 6) {
    $_SESSION['error_message'] = "Password must be at least 6 characters.";
  } else {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $update = "UPDATE users SET password = '$hashedPassword' WHERE user_code = '$userCode'";
    if (mysqli_query($conn, $update)) {
      $_SESSION['success_message'] = "Password changed successfully.";
    } else {
      $_SESSION['error_message'] = "Password update failed: " . mysqli_error($conn);
    }
  }

  mysqli_close($conn);
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}


$profileImage = isset($_SESSION['profile_image'])
  && $_SESSION['profile_image']
  ? "uploads/{$_SESSION['profile_image']}"
  : "https://placehold.co/80";

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manager Settings</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
</head>
<style>
  .mgr-set-tabs button.active {
    background-color: rgb(91, 91, 91);
    color: white;
  }
</style>

<body class="manager">
  <div class="mgr-container ">
    <?php include "./php/includes/mgr_sidebar.php"; ?>
    <div class="acc-container">
      <div class="acc-header">
        <div class="acc-header-text">
          <div class="upload-container">
            <div class="profile-pic" id="pic">
              <img src="<?= $profileImage ?>" alt="Profile Picture"
                style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
            </div>

            <form method="POST" action="./php/upload.php" enctype="multipart/form-data" id="uploadForm">
              <input type="file" id="fileInput" name="profile_image" accept="image/*" required>
              <button type="submit" name="upload_image" class="upload-button">Upload</button>
            </form>
          </div>

          <h2><?php echo $_SESSION['full_name']; ?></h2>
          <p>Employee ID: <?php echo $_SESSION['user_code']; ?></p>
        </div>
      </div>

      <div class="tabs">
        <button onclick="showPersonal()">PERSONAL</button>
        <button onclick="showEmployment()">EMPLOYMENT</button>
        <button onclick="showEmergency()">EMERGENCY</button>
      </div>
      <div class="acc-content-personal">
        <h3>Personal Information</h3>
        <div class="info-grid">
          <div class="info-item">
            <strong>Full Name</strong>
            <span><?= htmlspecialchars($_SESSION['full_name'] ?? '') ?></span>
          </div>
          <div class="info-item">
            <strong>Date of Birth</strong>
            <span><?= htmlspecialchars($_SESSION['date_of_birth'] ?? '') ?></span>
          </div>
          <div class="info-item">
            <strong>Place of Birth</strong>
            <span><?= htmlspecialchars($_SESSION['place_of_birth'] ?? '') ?></span>
          </div>
          <div class="info-item">
            <strong>Gender</strong>
            <span><?= htmlspecialchars($_SESSION['gender'] ?? '') ?></span>
          </div>
          <div class="info-item" id="civilStatusContainer">
            <strong>Civil Status</strong>

            <!-- Default View -->
            <div id="civilStatusView">
              <span><?= htmlspecialchars($_SESSION['civil_status'] ?? '') ?></span>
              <button class="edit-acc-btn" onclick="showForm('civilStatus')">Edit</button>
            </div>

            <!-- Edit Form -->
            <form method="POST" id="civilStatusForm" style="display:none;">
              <select name="civil_status" class="edit-input" required>
                <?php
                $options = ['Single', 'Married', 'Widowed', 'Separated', 'Divorced'];
                $current = $_SESSION['civil_status'] ?? '';
                foreach ($options as $option) {
                  $selected = ($current === $option) ? 'selected' : '';
                  echo "<option value=\"$option\" $selected>$option</option>";
                }
                ?>
              </select>
              <input type="hidden" name="field" value="civil_status">
              <button type="submit" name="update_field" class="save-btn">Save</button>
              <button type="button" onclick="hideForm('civilStatus')" class="cancel-btn">Cancel</button>
            </form>
          </div>


          <div class="info-item">
            <strong>Nationality</strong>
            <div id="nationalityView">
              <span><?= htmlspecialchars($_SESSION['nationality'] ?? '') ?></span>
              <button class="edit-acc-btn" onclick="showForm('nationality')">Edit</button>
            </div>
            <form method="POST" id="nationalityForm" style="display:none;">
              <input type="text" name="nationality" class="edit-input"
                value="<?= htmlspecialchars($_SESSION['nationality'] ?? '') ?>" required>
              <input type="hidden" name="field" value="nationality">
              <button type="submit" name="update_field" class="save-btn">Save</button>
              <button type="button" onclick="hideForm('nationality')" class="cancel-btn">Cancel</button>
            </form>
          </div>
          <div class="info-item">
            <strong>Phone</strong>
            <div id="phoneView">
              <span><?= htmlspecialchars($_SESSION['phone'] ?? '') ?></span>
              <button class="edit-acc-btn" onclick="showForm('phone')">Edit</button>
            </div>
            <form method="POST" id="phoneForm" style="display:none;">
              <input type="text" name="phone" class="edit-input"
                value="<?= htmlspecialchars($_SESSION['phone'] ?? '') ?>" required>
              <input type="hidden" name="field" value="phone">
              <button type="submit" name="update_field" class="save-btn">Save</button>
              <button type="button" onclick="hideForm('phone')" class="cancel-btn">Cancel</button>
            </form>

          </div>
          <div class="info-item">
            <strong>Email</strong>
            <div id="emailView">
              <span><?= htmlspecialchars($_SESSION['email'] ?? '') ?></span>
              <button class="edit-acc-btn" onclick="showForm('email')">Edit</button>
            </div>
            <form method="POST" id="emailForm" style="display:none;">
              <input type="email" name="email" class="edit-input"
                value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" required>
              <input type="hidden" name="field" value="email">
              <button type="submit" name="update_field" class="save-btn">Save</button>
              <button type="button" onclick="hideForm('email')" class="cancel-btn">Cancel</button>
            </form>

          </div>
          <div class="info-item">
            <strong>Address</strong>
            <div id="addressView">
              <span><?= htmlspecialchars($_SESSION['address'] ?? '') ?></span>
              <button class="edit-acc-btn" onclick="showForm('address')">Edit</button>
            </div>
            <form method="POST" id="addressForm" style="display:none;">
              <input type="text" name="address" class="edit-input"
                value="<?= htmlspecialchars($_SESSION['address'] ?? '') ?>" required>
              <input type="hidden" name="field" value="address">
              <button type="submit" name="update_field" class="save-btn">Save</button>
              <button type="button" onclick="hideForm('address')" class="cancel-btn">Cancel</button>
            </form>

          </div>
        </div>
      </div>

      <div class="acc-content-employment" style="display:none;">
        <h3>Employment Information</h3>
        <div class="info-grid">
          <div class="info-item">
            <strong>Start Date</strong>
            <span><?= htmlspecialchars($_SESSION['start_date'] ?? '') ?></span>
          </div>
          <div class="info-item">
            <strong>End Date</strong>
            <span><?= htmlspecialchars($_SESSION['end_date'] ?? '-') ?></span>
          </div>
          <div class="info-item">
            <strong>Date Created</strong>
            <span><?= htmlspecialchars($_SESSION['created_at'] ?? '') ?></span>
          </div>
          <div class="info-item">
            <strong>Job Title</strong>
            <span><?= htmlspecialchars($_SESSION['job_title'] ?? '') ?></span>
          </div>
          <div class="info-item">
            <strong>Role</strong>
            <span><?= htmlspecialchars(ucfirst($_SESSION['role']) ?? '') ?></span>
          </div>
        </div>
        <div class="acc-content-password">
          <h3>Change Password</h3>
          <form method="POST" style="max-width: 400px;">
            <div class="info-item">
              <strong>Current Password</strong>
              <input type="password" name="current_password" class="edit-input" required>
            </div>
            <div class="info-item">
              <strong>New Password</strong>
              <input type="password" name="new_password" class="edit-input" required>
            </div>
            <div class="info-item">
              <strong>Confirm New Password</strong>
              <input type="password" name="confirm_password" class="edit-input" required>
            </div>
            <button type="submit" name="change_password" class="add-task-btn" style="margin-top: 10px;">Change
              Password</button>
          </form>
        </div>
      </div>

      <div class="acc-content-emergency" style="display:none;">
        <h3>Emergency Information</h3>
        <div class="info-grid">
          <div class="info-item">
            <strong>Emergency Contact Name:</strong>
            <div id="emergencyContactNameView">
              <span><?= htmlspecialchars($_SESSION['emergency_contact_name'] ?? '') ?></span>
              <button class="edit-acc-btn" onclick="showForm('emergencyContactName')">Edit</button>
            </div>
            <form method="POST" id="emergencyContactNameForm" style="display:none;">
              <input type="text" name="emergency_contact_name" class="edit-input"
                value="<?= htmlspecialchars($_SESSION['emergency_contact_name'] ?? '') ?>" required>
              <input type="hidden" name="field" value="emergency_contact_name">
              <button type="submit" name="update_field" class="save-btn">Save</button>
              <button type="button" onclick="hideForm('emergencyContactName')" class="cancel-btn">Cancel</button>
            </form>

          </div>
          <div class="info-item">
            <strong>Relationship:</strong>
            <div id="relationshipView">
              <span><?= htmlspecialchars($_SESSION['emergency_relationship'] ?? '') ?></span>
              <button class="edit-acc-btn" onclick="showForm('relationship')">Edit</button>
            </div>
            <form method="POST" id="relationshipForm" style="display:none;">
              <input type="text" name="emergency_relationship" class="edit-input"
                value="<?= htmlspecialchars($_SESSION['emergency_relationship'] ?? '') ?>" required>
              <input type="hidden" name="field" value="emergency_relationship">
              <button type="submit" name="update_field" class="save-btn">Save</button>
              <button type="button" onclick="hideForm('relationship')" class="cancel-btn">Cancel</button>
            </form>

          </div>
          <div class="info-item">
            <strong>Contact Number:</strong>
            <div id="emergencyContactNumberView">
              <span><?= htmlspecialchars($_SESSION['emergency_phone'] ?? '') ?></span>
              <button class="edit-acc-btn" onclick="showForm('emergencyContactNumber')">Edit</button>
            </div>
            <form method="POST" id="emergencyContactNumberForm" style="display:none;">
              <input type="text" name="emergency_phone" class="edit-input"
                value="<?= htmlspecialchars($_SESSION['emergency_phone'] ?? '') ?>" required>
              <input type="hidden" name="field" value="emergency_phone">
              <button type="submit" name="update_field" class="save-btn">Save</button>
              <button type="button" onclick="hideForm('emergencyContactNumber')" class="cancel-btn">Cancel</button>
            </form>
          </div>
        </div>
      </div>
      <?php if (!empty($success_message)): ?>
        <div style="color: green;"><?= $success_message ?></div>
      <?php endif; ?>

      <?php if (!empty($error_message)): ?>
        <div style="color: red;"><?= $error_message ?></div>
      <?php endif; ?>

      <button class="log-out-btn">Log out</button>

      <div id="logoutModal" class="modal">
        <div class="modal-content">
          <span class="close" onclick="closeModal()">&times;</span>
          <h3>Are you sure you want to log out?</h3>
          <a href="./logout.php" class="confirm-logout-btn">Yes</a>
          <a class="cancel-logout-btn" onclick="closeModal()">No</a>
        </div>
      </div>
      <script>
        function showPersonal() {
          document.querySelector('.acc-content-personal').style.display = 'block';
          document.querySelector('.acc-content-employment').style.display = 'none';
          document.querySelector('.acc-content-emergency').style.display = 'none';
        }
        function showEmployment() {
          document.querySelector('.acc-content-personal').style.display = 'none';
          document.querySelector('.acc-content-employment').style.display = 'block';
          document.querySelector('.acc-content-emergency').style.display = 'none';
        }
        function showEmergency() {
          document.querySelector('.acc-content-personal').style.display = 'none';
          document.querySelector('.acc-content-employment').style.display = 'none';
          document.querySelector('.acc-content-emergency').style.display = 'block';
        }
        window.onload = function () {
          showPersonal();
        };

        function showForm(fieldName) {
          document.getElementById(fieldName + 'View').style.display = 'none';
          document.getElementById(fieldName + 'Form').style.display = 'flex';
        }

        function hideForm(fieldName) {
          document.getElementById(fieldName + 'Form').style.display = 'none';
          document.getElementById(fieldName + 'View').style.display = 'block';
        }

        const modal = document.getElementById("logoutModal");
        const logoutButton = document.querySelector(".log-out-btn");
        logoutButton.onclick = function () {
          modal.style.display = "flex";
        }
        function closeModal() {
          modal.style.display = "none";
        }
        window.onclick = function (event) {
          if (event.target == modal) {
            closeModal();
          }
        }

      </script>
    </div>
</body>

</html>