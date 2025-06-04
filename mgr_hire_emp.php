<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hire New Employee</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
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
  <!-- Sidebar -->
  <?php include "./php/includes/mgr_sidebar.php"; ?>

  <!-- Main Content -->
  <main class="mgr-content">
    <header class="mgr-header">
      <div>
        <h1>Welcome, Glenda</h1>
        <p class="mgr-id"><b>Branch Manager </b> || ID No.: 0001</p>
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


              <div id="formErrors" class="error-container" style="color: red; margin-bottom: 15px; display: none;">
              </div>

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
                <label>ID No. <input type="text" name="id_number" readonly
                    placeholder="Will be generated automatically" /></label>
                <h3>Personal Information</h3>
                <label>First Name <input type="text" name="first_name" required /></label>
                <label>Last Name <input type="text" name="last_name" required /></label>
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

    // Open modal on REGISTER click
    document.getElementById('openRegisterModal').addEventListener('click', function () {
      document.getElementById('registerModal').classList.remove('hidden');
    });

    // Close modal when clicking outside
    window.addEventListener('click', function (event) {
      const modal = document.getElementById('registerModal');
      if (event.target === modal) {
        modal.classList.add('hidden');
      }
    });

    // Add close button functionality (if you have one)
    document.addEventListener('click', function (event) {
      if (event.target.classList.contains('modal-close')) {
        document.getElementById('registerModal').classList.add('hidden');
      }
    });

    // Preview profile image
    function previewProfileImage(event) {
      if (event.target.files && event.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
          const output = document.getElementById('profileImagePreview');
          if (output) {
            output.src = e.target.result;
          }
        };
        reader.readAsDataURL(event.target.files[0]);
      }
    }

    // Email & Phone Validators
    function isValidEmail(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function isValidPhone(phone) {
      return /^[0-9\-\+\s\(\)]{7,20}$/.test(phone);
    }

    // Show loading overlay
    function showLoading(message = 'Processing...') {
      const existingOverlay = document.getElementById('loadingOverlay');
      if (existingOverlay) {
        existingOverlay.remove();
      }

      const overlay = document.createElement('div');
      overlay.id = 'loadingOverlay';
      overlay.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    color: white;
    font-size: 18px;
  `;
      overlay.innerHTML = `
    <div style="text-align: center; background: rgba(255,255,255,0.1); padding: 30px; border-radius: 10px; backdrop-filter: blur(5px);">
      <div style="width: 50px; height: 50px; border: 3px solid #fff; border-top: 3px solid transparent; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
      <p style="margin: 0;">${message}</p>
    </div>
  `;

      // Add CSS animation
      if (!document.getElementById('spinAnimation')) {
        const style = document.createElement('style');
        style.id = 'spinAnimation';
        style.textContent = `
      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
    `;
        document.head.appendChild(style);
      }

      document.body.appendChild(overlay);
    }

    function hideLoading() {
      const overlay = document.getElementById('loadingOverlay');
      if (overlay) {
        overlay.remove();
      }
    }

    // Enhanced success message
    function showSuccessMessage(employeeId, emailSent) {
      const message = `
    <div style="
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: linear-gradient(135deg, #28a745, #20c997);
      color: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
      z-index: 10000;
      text-align: center;
      min-width: 300px;
      max-width: 500px;
    ">
      <div style="font-size: 48px; margin-bottom: 15px;">✅</div>
      <h3 style="margin: 0 0 15px 0; font-size: 24px;">Registration Successful!</h3>
      <p style="margin: 10px 0; font-size: 14px;">
        ${emailSent ? '📧 Welcome email sent successfully!' : '⚠️ Registration completed but email failed to send.'}
      </p>
      <button onclick="this.parentElement.remove()" style="
        background: rgba(255,255,255,0.2);
        border: 2px solid white;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 15px;
        font-size: 16px;
      ">Close</button>
    </div>
  `;

      const messageEl = document.createElement('div');
      messageEl.innerHTML = message;
      document.body.appendChild(messageEl);

      // Auto-remove after 8 seconds
      setTimeout(() => {
        if (messageEl.parentElement) {
          messageEl.remove();
        }
      }, 8000);
    }

    const messageEl = document.createElement('div');
    messageEl.innerHTML = message;
    document.body.appendChild(messageEl);

    // Auto-remove after 8 seconds
    setTimeout(() => {
      if (messageEl.parentElement) {
        messageEl.remove();
      }
    }, 8000);


    // Enhanced error message
    function showErrorMessage(errors) {
      const errorContainer = document.getElementById('formErrors');
      if (!errorContainer) return;

      errorContainer.style.display = 'block';
      errorContainer.innerHTML = `
    <div style="
      background: linear-gradient(135deg, #dc3545, #c82333);
      color: white;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    ">
      <div style="display: flex; align-items: center; margin-bottom: 15px;">
        <span style="font-size: 24px; margin-right: 10px;">⚠️</span>
        <h4 style="margin: 0; font-size: 18px;">Registration Failed</h4>
      </div>
      <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 8px;">
        ${Array.isArray(errors) ?
          errors.map(error => `<p style="margin: 8px 0; padding-left: 15px; position: relative;">
            <span style="position: absolute; left: 0;">•</span> ${error}
          </p>`).join('') :
          `<p style="margin: 0;">${errors}</p>`
        }
      </div>
    </div>`;

      // Scroll to error
      errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // AJAX Submit Handler
    document.addEventListener("DOMContentLoaded", function () {
      const form = document.getElementById('registrationForm');

      if (!form) {
        console.error('Registration form not found');
        return;
      }

      form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const errorContainer = document.getElementById('formErrors');
        const submitBtn = form.querySelector('button[type="submit"]');

        // Reset previous errors
        if (errorContainer) {
          errorContainer.style.display = 'none';
          errorContainer.innerHTML = '';
        }

        // Client-side validation
        const requiredFields = [
          { field: 'first_name', label: 'First Name' },
          { field: 'last_name', label: 'Last Name' },
          { field: 'email', label: 'Email' },
          { field: 'dob', label: 'Date of Birth' },
          { field: 'gender', label: 'Gender' },
          { field: 'start_date', label: 'Start Date' },
          { field: 'date_created', label: 'Date Created' },
          { field: 'role', label: 'Role' }
        ];

        let hasError = false;
        let errorMessages = [];

        // Check required fields
        requiredFields.forEach(item => {
          const value = formData.get(item.field);
          if (!value || value.trim() === '') {
            hasError = true;
            errorMessages.push(`${item.label} is required`);
          }
        });

        // Validate email format
        const email = formData.get('email');
        if (email && !isValidEmail(email)) {
          hasError = true;
          errorMessages.push('Please enter a valid email address');
        }

        // Validate phone number if provided
        const phone = formData.get('phone_number');
        if (phone && phone.trim() !== '' && !isValidPhone(phone)) {
          hasError = true;
          errorMessages.push('Please enter a valid phone number');
        }

        // Validate dates
        const dob = new Date(formData.get('dob'));
        const today = new Date();
        if (dob >= today) {
          hasError = true;
          errorMessages.push('Date of Birth must be in the past');
        }

        const startDate = new Date(formData.get('start_date'));
        const dateCreated = new Date(formData.get('date_created'));

        // Check that start_date is not before date_created
        if (startDate < dateCreated) {
          hasError = true;
          errorMessages.push('Start Date cannot be earlier than Date Created');
        }

        // If errors exist, show them and return
        if (hasError) {
          showErrorMessage(errorMessages);
          return;
        }

        // Disable submit button to prevent double submission
        if (submitBtn) submitBtn.disabled = true;
        showLoading('Submitting your registration...');

        // Make AJAX call to register_employee.php
        fetch('register_employee.php', {
          method: 'POST',
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            hideLoading();
            if (submitBtn) submitBtn.disabled = false;

            if (data.success) {
              // Clear form and show success message
              form.reset();
              document.getElementById('profileImagePreview').src = 'assets/img/default-profile.png';
              document.getElementById('registerModal').classList.add('hidden');
              showSuccessMessage(data.employee_id, data.email_sent);
            } else {
              showErrorMessage(data.message || 'Registration failed');
            }
          })
          .catch(error => {
            hideLoading();
            if (submitBtn) submitBtn.disabled = false;
            showErrorMessage('Network error. Please try again.');
            console.error('Error:', error);
          });
      });
    });








  </script>



</body>

</html>