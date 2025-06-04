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
    <!-- Sidebar -->
    <?php include "./php/includes/mgr_sidebar.php"; ?>

    <div class="mgr-set-acc-container">
      <div class="mgr-set-acc-header">
        <div class="mgr-set-acc-header-text">
          <div class="mgr-set-profile-pic" id="mgr-set-pic">
            <span>No image uploaded</span>
          </div>
          <input type="file" id="mgr-set-fileInput" accept="image/*" class="mgr-set-upload-btn" />
          <h2>Tralalero Tralala</h2>
          <p>Employee ID: 6969</p>
          <p>Employee</p>
        </div>
      </div>

      <div class="mgr-set-tabs">
        <button id="btnPersonal" onclick="showPersonal()">Personal</button>
        <button id="btnEmployment" onclick="showEmployment()">Employment</button>
        <button id="btnEmergency" onclick="showEmergency()">Emergency</button>
      </div>

      <div class="mgr-set-action-buttons" style="margin-top: 20px; text-align: right;">
        <button id="changePasswordBtn" class="mgr-set-log-out-btn"
          style="background-color:rgb(97, 97, 97); margin-right: 10px;">
          Change Password
        </button>
        <button id="logoutBtn" class="mgr-set-log-out-btn" style="background-color:rgb(229, 26, 47);">
          Logout
        </button>
      </div>

      <div class="mgr-set-acc-content-personal">
        <h3>Personal Information</h3>
        <div class="mgr-set-info-grid">
          <div class="mgr-set-info-item">
            <strong>Full Name:</strong>
          </div>
          <div class="mgr-set-info-item">
            <strong>Date of Birth:</strong>
          </div>
          <div class="mgr-set-info-item">
            <strong>Place of Birth:</strong>
          </div>
          <div class="mgr-set-info-item">
            <strong>Gender:</strong>
          </div>
          <div class="mgr-set-info-item">
            <strong>Civil Status:</strong>
            <span id="mgr-set-civilStatus"></span>
            <button class="mgr-set-edit-acc-btn" onclick="editField('mgr-set-civilStatus')">Edit</button>
          </div>
          <div class="mgr-set-info-item">
            <strong>Nationality:</strong>
            <span id="mgr-set-nationality"></span>
            <button class="mgr-set-edit-acc-btn" onclick="editField('mgr-set-nationality')">Edit</button>
          </div>
          <div class="mgr-set-info-item">
            <strong>Phone:</strong>
            <span id="mgr-set-phone"></span>
            <button class="mgr-set-edit-acc-btn" onclick="editField('mgr-set-phone')">Edit</button>
          </div>
          <div class="mgr-set-info-item">
            <strong>Email:</strong>
            <span id="mgr-set-email"></span>
            <button class="mgr-set-edit-acc-btn" onclick="editField('mgr-set-email')">Edit</button>
          </div>
          <div class="mgr-set-info-item">
            <strong>Address:</strong>
            <span id="mgr-set-address"></span>
            <button class="mgr-set-edit-acc-btn" onclick="editField('mgr-set-address')">Edit</button>
          </div>
        </div>
      </div>

      <div class="mgr-set-acc-content-employment" style="display:none;">
        <h3>Employment Information</h3>
        <div class="mgr-set-info-grid">
          <div class="mgr-set-info-item">
            <strong>Password:</strong>
          </div>
          <div class="mgr-set-info-item">
            <strong>Change Password:</strong>
            <span id="mgr-set-changePassword"></span>
            <button class="mgr-set-edit-acc-btn" onclick="editField('mgr-set-changePassword')">Edit</button>
          </div>
          <div class="mgr-set-info-item">
            <strong>Start Date:</strong>
          </div>
          <div class="mgr-set-info-item">
            <strong>End Date:</strong>
          </div>
          <div class="mgr-set-info-item">
            <strong>Date Created:</strong>
          </div>
          <div class="mgr-set-info-item">
            <strong>Department:</strong>
          </div>
          <div class="mgr-set-info-item">
            <strong>Role:</strong>
          </div>
        </div>
      </div>

      <div class="mgr-set-acc-content-emergency" style="display:none;">
        <h3>Emergency Information</h3>
        <div class="mgr-set-info-grid">
          <div class="mgr-set-info-item">
            <strong>Emergency Contact Name:</strong>
            <span id="mgr-set-emergencyContactName"></span>
            <button class="mgr-set-edit-acc-btn" onclick="editField('mgr-set-emergencyContactName')">Edit</button>
          </div>
          <div class="mgr-set-info-item">
            <strong>Relationship:</strong>
            <span id="mgr-set-relationship"></span>
            <button class="mgr-set-edit-acc-btn" onclick="editField('mgr-set-relationship')">Edit</button>
          </div>
          <div class="mgr-set-info-item">
            <strong>Contact Number:</strong>
            <span id="mgr-set-emergencyContactNumber"></span>
            <button class="mgr-set-edit-acc-btn" onclick="editField('mgr-set-emergencyContactNumber')">Edit</button>
          </div>
        </div>
      </div>
    </div>


  </div>

  <div id="logoutModal" class="modal" style="display:none;">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <p>Are you sure you want to logout?</p>
      <div class="row">
        <a href="./logout.php" class="confirm-logout-btn">Yes</a>
        <a onclick="closeModal()">No</a>
      </div>
    </div>
  </div>

  <script>
    const fileInput = document.getElementById('mgr-set-fileInput');
    const pic = document.getElementById('mgr-set-pic');

    fileInput.addEventListener('change', (event) => {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = () => {
          const imageData = reader.result;
          localStorage.setItem('profilePic', imageData);


          pic.innerHTML = '';
          const img = document.createElement('img');
          img.src = imageData;
          img.style.width = "50px";
          img.style.height = "50px";
          img.style.objectFit = "cover";
          pic.appendChild(img);
        };
        reader.readAsDataURL(file);
      } else {
        pic.innerHTML = '<span>No image uploaded</span>';
        localStorage.removeItem('profilePic');
      }
    });


    window.onload = () => {
      const savedImage = localStorage.getItem('profilePic');
      if (savedImage) {
        pic.innerHTML = '';
        const img = document.createElement('img');
        img.src = savedImage;
        img.style.width = "50px";
        img.style.height = "50px";
        img.style.objectFit = "cover";
        pic.appendChild(img);
      }
      showPersonal();
      setActiveTab('btnPersonal');
    };

    function clearActiveTabs() {
      const buttons = document.querySelectorAll('.mgr-set-tabs button');
      buttons.forEach(btn => btn.classList.remove('active'));
    }

    function showPersonal() {
      clearActiveTabs();
      document.querySelector('.mgr-set-acc-content-personal').style.display = 'block';
      document.querySelector('.mgr-set-acc-content-employment').style.display = 'none';
      document.querySelector('.mgr-set-acc-content-emergency').style.display = 'none';
      document.querySelector('.mgr-set-tabs button:nth-child(1)').classList.add('active');
    }

    function showEmployment() {
      clearActiveTabs();
      document.querySelector('.mgr-set-acc-content-personal').style.display = 'none';
      document.querySelector('.mgr-set-acc-content-employment').style.display = 'block';
      document.querySelector('.mgr-set-acc-content-emergency').style.display = 'none';
      document.querySelector('.mgr-set-tabs button:nth-child(2)').classList.add('active');
    }

    function showEmergency() {
      clearActiveTabs();
      document.querySelector('.mgr-set-acc-content-personal').style.display = 'none';
      document.querySelector('.mgr-set-acc-content-employment').style.display = 'none';
      document.querySelector('.mgr-set-acc-content-emergency').style.display = 'block';
      document.querySelector('.mgr-set-tabs button:nth-child(3)').classList.add('active');
    }


    window.onload = function () {
      showPersonal();
    };



    function setActiveTab(buttonId) {
      ['btnPersonal', 'btnEmployment', 'btnEmergency'].forEach(id => {
        document.getElementById(id).classList.toggle('active', id === buttonId);
      });
    }

    function editField(fieldId) {
      const container = document.getElementById(fieldId);

      if (container.querySelector('input')) {
        return;
      }
      const currentValue = container.textContent.trim();
      container.textContent = '';

      const input = document.createElement('input');
      input.type = 'text';
      input.className = 'edit-input';
      input.value = currentValue;
      container.appendChild(input);

      const saveBtn = document.createElement('button');
      saveBtn.textContent = 'Save';
      saveBtn.className = 'save-btn';
      saveBtn.onclick = () => {
        container.textContent = input.value.trim();
      };

      const cancelBtn = document.createElement('button');
      cancelBtn.textContent = 'Cancel';
      cancelBtn.className = 'cancel-btn';
      cancelBtn.onclick = () => {
        container.textContent = currentValue;
      };
      container.appendChild(saveBtn);
      container.appendChild(cancelBtn);
    }

    function closeModal() {
      document.querySelector(".modal").style.display = "none";
    }


    // const logoutModalHTML = `
    //   <div id="logoutModal" class="mgr-set-modal" style="display:none;">
    //     <div class="mgr-set-modal-content" style="padding:20px; max-width: 300px; margin: 15% auto; border-radius: 8px; background:#fff; text-align:center;">
    //       <span id="closeLogoutModal" style="float:right; cursor:pointer; font-size: 20px;">&times;</span>
    //       <p>Are you sure you want to logout?</p>
    //       <button id="confirmLogoutBtn" style="background-color:#dc3545; color:#fff; border:none; padding:10px 20px; margin:10px; border-radius:4px; cursor:pointer;">Yes</button>
    //       <button id="cancelLogoutBtn" style="background-color:#6c757d; color:#fff; border:none; padding:10px 20px; margin:10px; border-radius:4px; cursor:pointer;">No</button>
    //     </div>
    //   </div>
    // `;
    // document.body.insertAdjacentHTML('beforeend', logoutModalHTML);

    const logoutBtn = document.getElementById('logoutBtn');
    const logoutModal = document.getElementById('logoutModal');
    const closeLogoutModal = document.getElementById('closeLogoutModal');
    const confirmLogoutBtn = document.getElementById('confirmLogoutBtn');
    const cancelLogoutBtn = document.getElementById('cancelLogoutBtn');
    const changePasswordBtn = document.getElementById('changePasswordBtn');

    logoutBtn.addEventListener('click', () => {
      logoutModal.style.display = 'flex';
    });

    closeLogoutModal.addEventListener('click', () => {
      logoutModal.style.display = 'none';
    });

    cancelLogoutBtn.addEventListener('click', () => {
      logoutModal.style.display = 'none';
    });

    confirmLogoutBtn.addEventListener('click', () => {

      alert('Logged out successfully!');
      logoutModal.style.display = 'none';
      // For example, redirect to login page:
      // window.location.href = 'login.php';
    });

    changePasswordBtn.addEventListener('click', () => {
      // Trigger change password workflow or page
      alert('Change password clicked. Implement your logic here.');
    });



  </script>
</body>

</html>