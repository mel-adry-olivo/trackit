<?php

include "database/queries.php";

session_start();

$userFullname = $_SESSION['full_name'] ?? '';
$userCode = $_SESSION["user_code"] ?? null;

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hire New Employee</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <script src="./assets/js/main/clock.js" defer></script>
  <style>
    #loadingOverlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(0, 0, 0, 0.5);
      z-index: 9999;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
      font-family: sans-serif;
    }

    .loading-spinner {
      text-align: center;
    }

    .loading-spinner .spinner {
      width: 50px;
      height: 50px;
      border: 6px solid #fff;
      border-top: 6px solid #888;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin: 0 auto 10px auto;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }
  </style>
</head>

<body class="manager">

  <div class="top-banner" style="
  background: url('./assets/img/banners/9.jpg') no-repeat center center / cover;
    width: 100%;
    height: 40%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 0;
">
  </div>
  <?php include "./php/includes/mgr_sidebar.php"; ?>

  <main class="mgr-content">
    <header class="mgr-header">
      <div>
        <h1>Welcome, <?= $userFullname ?></h1>
        <p class="mgr-id"><b>Employee</b> || ID No.: <?= $userCode ?>
        </p>
      </div>
      <div class="mgr-branch-info">
        <span>Branch: <strong>WVSU-BINHI TBI</strong> | Time: </span>
        <span id="clock">--:--:--</span>
      </div>
    </header>

    <div class="mgr-title">
      <h1><br>Hire & Register</h1><br>
      <p>Please click the "Register" button and start filling up the personal biodata of your newest staff.</p>
      <br><br><br>
    </div>

    <div class="mgr-hire-filters">
      <div class="hire-filter-box">
        <img src="assets/img/reg-1.jpg" />
        <h3>Who to hire?</h3>
        <p>Hire proactive self-starters who take initiative early.</p>
      </div>

      <div class="hire-filter-box">
        <img src="assets/img/reg-2.jpg" />
        <h3>What skills are needed? </h3>
        <p>Hire adaptable, focused individuals with time management skills.</p>
      </div>

      <div class="hire-filter-box">
        <img src="assets/img/reg-3.jpg" />
        <h3>What abilities are needed? </h3>
        <p>Hire efficient team players who complete tasks thoroughly.</p>
      </div>

      <div class="hire-filter-box">
        <img src="assets/img/reg-4.jpg" />
        <h3>What brains are needed? </h3>
        <p>Hire reliable problem-solvers who handle pressure well.</p>
      </div>

      <button class="register-btn" id="openRegisterModal" onclick="toggleModal()">
        <img src="assets/svg/add.svg" class="btn-icon" />
        REGISTER
      </button>
    </div>

    <div id="registerModal" class="modal hidden">
      <div class="modal-content">
        <div style="display:inline; position: absolute; right: 1rem; top: 0.5rem;">
          <button type="button" onclick="closeModal()">X</button>
        </div>
        <form id="registrationForm" enctype="multipart/form-data">
          <div class="form-columns">
            <div class="form-card">
              <div id="formErrors" class="error-container" style="color: red; margin-bottom: 15px; display: none;">
              </div>
              <div class="profile-upload">
                <h3>Upload Profile Photo</h3>
                <div class="profile-row">
                  <div class="profile-preview">
                    <img id="profileImagePreview" src="https://placehold.co/40" alt="Profile Image Preview" />
                  </div>
                  <div class="profile-input">
                    <input type="file" name="profile_image" accept="image/*" onchange="previewProfileImage(event)" />
                  </div>
                </div>
              </div>
              <div class="person-info">
                <label>ID No. <input type="text" name="id_number" id="idNumber" readonly
                    placeholder="Will be generated automatically" /></label>
                <div class="row">
                  <label>First Name <input type="text" name="first_name" required /></label>
                  <label>Last Name <input type="text" name="last_name" required /></label>
                </div>
                <label>Date of Birth <input type="date" name="dob" required /></label>
                <label>Place of Birth <input type="text" name="birth_place" /></label>
                <div class="row">
                  <label>Gender
                    <select name="gender" required>
                      <option value="">Select gender...</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                      <option value="Other">Other</option>
                    </select>
                  </label>
                  <label>Civil Status
                    <select name="civil_status">
                      <option value="">Select status...</option>
                      <option value="Single">Single</option>
                      <option value="Married">Married</option>
                      <option value="Widowed">Widowed</option>
                    </select>
                  </label>
                </div>
                <div class="row">
                  <label>Nationality <input type="text" name="nationality" /></label>
                  <label>Phone <input type="text" name="phone_number" /></label>
                </div>
                <label>Email <input type="email" name="email" required /></label>
                <label>Address <input type="text" name="address" /></label>
              </div>
            </div>

            <div class="form-card">
              <div class="position-info">
                <h3>Employment Information</h3>
                <div class="row">
                  <label>Start Date <input type="date" name="start_date" required /></label>
                  <label>Job Title
                    <select name="job_title">
                      <option value="">Select job title...</option>
                      <option value="POS Manager">POS Manager</option>
                      <option value="Barista">Barista</option>
                      <option value="Staff">Staff</option>
                      <option value="Rider">Delivery Rider</option>
                    </select>
                  </label>
                </div>
              </div>

              <div class="emergency-info">
                <h3>Emergency Contact</h3>
                <label>Contact Name <input type="text" name="emergency_name" /></label>
                <div class="row">
                  <label>Relationship <input type="text" name="emergency_relation" /></label>
                  <label>Contact Number <input type="text" name="emergency_contact" /></label>
                </div>
              </div>

              <div class="notes-section">
                <h3>Notes</h3>
                <textarea name="notes" rows="4" placeholder="Additional remarks, observations, etc."></textarea>
              </div>
            </div>
          </div>
          <button type="submit" class="reg-submit-button">Submit</button>
        </form>
      </div>
    </div>
  </main>
  </div>

  <script>

    const modal = document.getElementById('registerModal');

    function toggleModal() {
      const modal = document.getElementById('registerModal');
      modal.classList.toggle('hidden');
      modal.style.display = 'flex';
    }

    function closeModal() {
      const modal = document.getElementById('registerModal');
      modal.classList.add('hidden');
      modal.style.display = 'none';
    }

    window.addEventListener('click', function (event) {
      const modal = document.getElementById('registerModal');
      if (event.target === modal) {
        closeModal();
      }
    });

    document.getElementById('openRegisterModal')
      .addEventListener('click', async () => {
        toggleModal();
        const role = 'employee';
        try {
          const resp = await fetch(`./php/next_user_code.php?role=${role}`);
          const { next_code } = await resp.json();
          document.getElementById('idNumber').value = next_code;
        } catch (err) {
          console.error("Couldn't fetch next code", err);
        }
      });

    window.onload = () => {
      document.getElementById('registrationForm').addEventListener('submit', async e => {
        e.preventDefault();
        const form = e.target;
        const data = new FormData(form);

        showLoading('Submitting registration…');
        try {
          const res = await fetch('./php/register_employee.php', {
            method: 'POST',
            body: data
          });
          const json = await res.json();
          hideLoading();

          if (json.success) {
            form.reset();
            document.getElementById('profileImagePreview').src = 'https://placeholder.co/40';
            closeModal();
            showSuccessMessage(json.employee_id, json.email_sent);
          }
        } catch (err) {
          hideLoading();
          alert('Invalid server response. Check console.');
          console.error(err);
        }
      });


    }
    function showLoading(message = 'Loading…') {
      const overlay = document.getElementById('loadingOverlay');
      overlay.querySelector('p').innerText = message;
      overlay.style.display = 'flex';
    }

    function hideLoading() {
      const overlay = document.getElementById('loadingOverlay');
      overlay.style.display = 'none';
    }
    function showSuccessMessage(employeeId, emailSent) {
      const toast = document.getElementById('successToast');
      const msg = document.getElementById('successToastMessage');

      msg.innerHTML = `Employee <strong>${employeeId}</strong> registered successfully.` +
        (emailSent ? ' Email sent ✅' : ' Email not sent ❌');

      toast.style.display = 'block';

      setTimeout(() => {
        toast.style.display = 'none';
      }, 5000);
    }

  </script>
  <div id="loadingOverlay" style="display:none;">
    <div class="loading-spinner">
      <div class="spinner"></div>
      <p>Processing… Please wait.</p>
    </div>
  </div>
  <div id="successToast" style="
  display: none;
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: #4CAF50;
  color: white;
  padding: 16px 24px;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  font-family: sans-serif;
  z-index: 10000;
">
    <strong>Success!</strong>
    <p id="successToastMessage" style="margin: 0;"></p>
  </div>


</body>

</html>