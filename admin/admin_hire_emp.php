<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Hire New Employee</title>
  <link rel="stylesheet" href="assets/css/admin_styles.css" />
</head>
<body class="manager">

<div class="top-banner" 

style="
  background: url('http://localhost/TestPK/assets/img/banners/23.jpg') no-repeat center center / cover;
    width: 100%;
    height: 40%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 0;
">

</div>


     <!-- Sidebar -->
     <aside class="mgr-sidebar ">
        <h2>TrackIT</h2>
          <ul>  
        <li>
        <a href="index.php" class="nav-link">
            <img class="nav-icon" src="assets/svg/dashboard.svg" alt="Dashboard Icon" width="16" height="16">
            Dashboard
          </a>
        </li>
        <li class="mgr-active">  
          <a href="admin_hire_emp.php" class="nav-link">
            <img class="nav-icon" src="assets/svg/hire.svg" alt="Hire Icon" width="16" height="16">
            Register Manager
          </a>
        </li>
        <li>
          <a href="admin_all_tasks.php" class="nav-link">            
          <img class="nav-icon" src="assets/svg/all-tasks.svg" alt="Tasks Icon" width="16" height="16">
            Overall Tasks
          </a>
        </li>
        <li>
          <a href="admin_assign_.php" class="nav-link">
            <img class="nav-icon" src="assets/svg/assign-task.svg" alt="Assign Icon" width="16" height="16">
            Assign Tasks
          </a>
        </li>
        <li>
          <a href="admin_reminder.php" class="nav-link">
            <img class="nav-icon" src="assets/svg/reminders.svg" alt="Reminder Icon" width="16" height="16">
            Reminders
          </a>
        </li>
        <li>
          <a href="admin_thistory.php" class="nav-link">
            <img class="nav-icon" src="assets/svg/task-history.svg" alt="Task History Icon" width="16" height="16">
            Task History
          </a>
        </li>
        <li>
          <a href="admin_comp.php" class="nav-link">
            <img class="nav-icon" src="assets/svg/company.svg" alt="Company Icon" width="16" height="16">
            Company
          </a>
        </li>
        <li>
          <a href="admin_rank.php" class="nav-link">
            <img class="nav-icon" src="assets/svg/rank.svg" alt="Rankings Icon" width="16" height="16">
            Rankings
          </a>
        </li>
        <li>
          <a href="admin_tracker.php" class="nav-link">
            <img class="nav-icon" src="assets/svg/tracker.svg" alt="Behavior Tracker Icon" width="16" height="16">
            Behavior Tracker
          </a>
        </li>

        <div class="admin-acc" class="nav-link">
          <li>
          <a href="admin_acc.php" class="nav-link">
            <img class="nav-icon" src="assets/svg/acc.svg" alt="Account Icon" width="16" height="16">
            Account
          </a>
          </li>
        </div>
        <li>
        <a href="admin_settings.php" class="nav-link">
          <img class="nav-icon" src="assets/svg/settings.svg" alt="Settings Icon" width="16" height="16">
          Settings
          </a>
        </li>
      </ul>
    </ul>
</aside>


    <!-- Main Content -->
    <main class="mgr-content">
      <header class="mgr-header">
     
      <div>
          <h1>Welcome, Paul</h1>
          <p class="mgr-id"><b>CEO & Founder </b> || ID No.: PKA001</p>
        </div>
        <div class="mgr-branch-info">
          <span>Branch: <strong>WVSU-BINHI TBI</strong> | Time: </span>
          <span id="clock">--:--:--</span>
        </div>
 
      </header>

      <div class="mgr-title">
        <h1><br>Hire & Register</h1><br>
        <p>Please click the "Register" button and start filling up the personal biodata of your branch manager.</p><br><br><br>
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

        <div class="register-btn" id="openRegisterModal">
          <img src="assets/svg/add.svg" class="btn-icon" />
          REGISTER
        </div>
      </div>

      <!-- MODAL OVERLAY - PERSONAL BIODATA -->
      <div id="registerModal" class="modal hidden">
        <div class="modal-content">
          <form id="registrationForm">
            <div class="form-columns">
              <!-- Left Column -->
              <div class="form-card">
                
                <!-- Profile Upload -->
                <div class="profile-upload">
                <h3>Upload Profile Photo</h3>
                <div class="profile-row">
                  <div class="profile-preview">
                    <img id="profileImagePreview" src="assets/img/default-profile.png" alt="Profile Image Preview" />
                  </div>
                  <div class="profile-input">
                    <input type="file" name="profile_image" accept="image/*" onchange="previewProfileImage(event)" />
                  </div>
                </div>
              </div>


                <!-- Personal Information -->
                <div class="person-info">

                <h3> ID Number</h3>
                  <label>ID No. <input type="text" name="id_number" required /></label>
                  <h3>Personal Information</h3>
                  <label>Full Name <input type="text" name="person_name" required /></label>
                  <label>Date of Birth <input type="date" name="dob" required /></label>
                  <label>Place of Birth <input type="text" name="birth_place" /></label>
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
                  <label>Nationality <input type="text" name="nationality" /></label>
                  <label>Phone <input type="text" name="phone_number" /></label>
                  <label>Email <input type="email" name="email" required /></label>
                  <label>Address <input type="text" name="address" /></label>
                </div>
              </div>

              <!-- Right Column -->
              <div class="form-card">
                <!-- Employment Information -->
                <div class="position-info">
                  <h3>Employment Information</h3>
                  <label>Job Title <input type="text" name="department" /></label>
                  <label>Start Date <input type="date" name="start_date" required /></label>

                  <label>Date Created <input type="date" name="date_created" required /></label>
                  <label>Role
                    <select name="role">
                      <option value="">Select role...</option>
                      <option value="POS Manager">POS Manager</option>
                      <option value="Barista">Barista</option>
                      <option value="Staff">Staff</option>
                      <option value="Rider">Delivery Rider</option>
                    </select>
                  </label>
                </div>

                <!-- Emergency Contact -->
                <div class="emergency-info">
                  <h3>Emergency Contact</h3>
                  <label>Contact Name <input type="text" name="emergency_name" /></label>
                  <label>Relationship <input type="text" name="emergency_relation" /></label>
                  <label>Contact Number <input type="text" name="emergency_contact" /></label>
                </div>

                <!-- Notes -->
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
    // Function to update clock
    function updateClock() {
      const now = new Date();
      document.getElementById("clock").textContent = now.toLocaleTimeString();
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Toggle modal visibility
    function toggleModal() {
      const modal = document.getElementById('registerModal');
      modal.classList.toggle('hidden');
    }

    // Open the modal when the "REGISTER" button is clicked
    document.getElementById('openRegisterModal').addEventListener('click', function() {
      document.getElementById('registerModal').classList.remove('hidden');
    });

    // Close the modal if clicked outside the modal content
    window.addEventListener('click', function(event) {
      const modal = document.getElementById('registerModal');
      if (event.target === modal) {
        modal.classList.add('hidden');
      }
    });

    // Preview the uploaded profile image
    function previewProfileImage(event) {
      const reader = new FileReader();
      reader.onload = function(){
        const output = document.getElementById('profileImagePreview');
        output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    }
  </script>
</body>
</html>
