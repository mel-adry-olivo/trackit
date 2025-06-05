<?php

session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manager Account</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>

<body class="manager">

  </div>
  <div class="mgr-container">
    <?php include "./php/includes/mgr_sidebar.php"; ?>

    <div class="acc-container">
      <div class="acc-header">
        <div class="acc-header-text">
          <img src="https://placehold.co/80" id="acc-pic" alt="Profile Picture">
          <h2><?php echo $_SESSION['full_name']; ?></h2>
          <p>Employee ID: <?php echo $_SESSION['uid']; ?></p>
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
            <span><?php echo $_SESSION['full_name']; ?></span>
          </div>
          <div class="info-item">
            <strong>Date of Birth</strong>
            <span><?php echo $_SESSION['date_of_birth']; ?></span>
          </div>
          <div class="info-item">
            <strong>Place of Birth</strong>
            <span><?php echo $_SESSION['place_of_birth']; ?></span>
          </div>
          <div class="info-item">
            <strong>Gender</strong>
            <span><?php echo $_SESSION['gender']; ?></span>
          </div>
          <div class="info-item">
            <strong>Civil Status</strong>
            <span><?php echo $_SESSION['civil_status']; ?></span>
          </div>
          <div class="info-item">
            <strong>Nationality</strong>
            <span><?php echo $_SESSION['nationality']; ?></span>
          </div>
          <div class="info-item">
            <strong>Phone</strong>
            <span><?php echo $_SESSION['phone']; ?></span>
          </div>
          <div class="info-item">
            <strong>Email</strong>
            <span><?php echo $_SESSION['email']; ?></span>
          </div>
          <div class="info-item">
            <strong>Address</strong>
            <span><?php echo $_SESSION['address']; ?></span>
          </div>
        </div>
        <a href="emp_settings.php"><button class="add-task-btn">Update</button></a>
      </div>

      <div class="acc-content-employment">
        <h3>Employment Information</h3>
        <div class="info-grid">
          <div class="info-item">
            <strong>Start Date</strong>
            <span><?php echo $_SESSION['start_date']; ?></span>
          </div>
          <div class="info-item">
            <strong>End Date</strong>
            <span><?php echo $_SESSION['end_date'] ?? '-'; ?></span>
          </div>
          <div class="info-item">
            <strong>Date Created</strong>
            <span><?php echo $_SESSION['created_at']; ?></span>
          </div>
          <div class="info-item">
            <strong>Job Title</strong>
            <span><?php echo $_SESSION['job_title']; ?></span>
          </div>
          <div class="info-item">
            <strong>Role</strong>
            <span><?php echo ucfirst($_SESSION['role']); ?></span>
          </div>
        </div>
      </div>

      <div class="acc-content-emergency">
        <h3>Emergency Information</h3>
        <div class="info-grid">
          <div class="info-item">
            <strong>Contact Name</strong>
            <span><?php echo $_SESSION['emergency_contact_name']; ?></span>
          </div>
          <div class="info-item">
            <strong>Relationship</strong>
            <span><?php echo $_SESSION['emergency_relationship']; ?></span>
          </div>
          <div class="info-item">
            <strong>Contact Number</strong>
            <span><?php echo $_SESSION['emergency_phone']; ?></span>
          </div>
        </div>
        <a href="emp_settings.php"><button class="add-task-btn">Update</button></a>
      </div>
      <script>
        const accPic = document.getElementById('acc-pic');

        window.onload = () => {
          const savedImage = localStorage.getItem('profilePic');
          if (savedImage) {
            accPic.src = savedImage;
          }
          showPersonal(); // Default tab
        };

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
      </script>
</body>

</html>