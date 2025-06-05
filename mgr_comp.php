<?php

include "database/queries.php";

session_start();

$allUsers = getAllUsers();


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Company</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <script src="./assets/js/main/clock.js" defer></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="manager">

  <div class="top-banner" style="
    background: url('./assets/img/banners/21.jpg') no-repeat center center / cover;
    width: 100%;
    height: 40%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 0;
  ">
  </div>

  <div class="mgr-container">
    <!-- Sidebar -->
    <?php include "./php/includes/mgr_sidebar.php"; ?>

    <!--Content-->
    <main class="mgr-content">

      <div class="mgr-comp-container">
        <div class="mgr-comp-search-container">
          <input type="text" class="mgr-comp-search-box" placeholder="Search for employees...">
          <select class="mgr-comp-department-filter">
            <option value="">All Job Titles</option>
            <option value="ADMIN">Admin</option>
            <option value="BARISTA">Barista</option>
            <option value="POS MGMT.">POS Management</option>
            <option value="DELIVERY">Delivery</option>
          </select>
        </div>

        <div class="mgr-comp-employee-grid">


          <!-- Coffee Team -->
          <div class="mgr-comp-employee-card" data-department="ADMIN">
            <h3 class="mgr-comp-employee-name">Mr. Peter Paul Dayanan</h3>
            <p class="mgr-comp-employee-title">CEO & Founder</p>
            <span class="mgr-comp-employee-department">ADMIN</span>
            <div class="mgr-comp-employee-contact">
              <div class="mgr-comp-contact-item">
                <i class="fas fa-building"></i>
                <span>WVSU Binhi-TBI </span>
              </div>
              <div class="mgr-comp-contact-item">
                <i class="fas fa-coffee"></i>
                <span>Paul Kaldi Coffee</span>
              </div>
            </div>
          </div>

          <div class="mgr-comp-employee-card" data-department="BARISTA,POS MGMT.">
            <h3 class="mgr-comp-employee-name">Jeric Palermo</h3>
            <p class="mgr-comp-employee-title">Barista | POS Manager</p>
            <span class="mgr-comp-employee-department">BARISTA</span>
            <span class="mgr-comp-employee-department">POS MGMT.</span>
            <div class="mgr-comp-employee-contact">
              <div class="mgr-comp-contact-item">
                <i class="fas fa-building"></i>
                <span>WVSU Binhi-TBI</span>
              </div>
              <div class="mgr-comp-contact-item">
                <i class="fas fa-coffee"></i>
                <span>Paul Kaldi Coffee</span>
              </div>
            </div>
          </div>

          <div class="mgr-comp-employee-card" data-department="BARISTA,POS MGMT.">
            <h3 class="mgr-comp-employee-name">Maricel Torremucha</h3>
            <p class="mgr-comp-employee-title">Barista | POS Manager</p>
            <span class="mgr-comp-employee-department">BARISTA</span>
            <span class="mgr-comp-employee-department">POS MGMT.</span>
            <div class="mgr-comp-employee-contact">
              <div class="mgr-comp-contact-item">
                <i class="fas fa-building"></i>
                <span>WVSU Binhi-TBI</span>
              </div>
              <div class="mgr-comp-contact-item">
                <i class="fas fa-coffee"></i>
                <span>Paul Kaldi Coffee</span>
              </div>
            </div>
          </div>

          <div class="mgr-comp-employee-card" data-department="BARISTA,POS MGMT.">
            <h3 class="mgr-comp-employee-name">Marvin John Ramos</h3>
            <p class="mgr-comp-employee-title">Barista | POS Manager | Delivery</p>
            <span class="mgr-comp-employee-department">BARISTA</span>
            <span class="mgr-comp-employee-department">POS MGMT.</span>
            <span class="mgr-comp-employee-department">DELIVERY</span>
            <div class="mgr-comp-employee-contact">
              <div class="mgr-comp-contact-item">
                <i class="fas fa-building"></i>
                <span>WVSU Binhi-TBI </span>
              </div>
              <div class="mgr-comp-contact-item">
                <i class="fas fa-coffee"></i>
                <span>Paul Kaldi Coffee</span>
              </div>
            </div>
          </div>

          <div class="mgr-comp-employee-card" data-department="BARISTA,POS MGMT.">
            <h3 class="mgr-comp-employee-name">Ms. Glenda Baquiriza</h3>
            <p class="mgr-comp-employee-title">Branch Manager in Training | Barista | POS Manager</p>
            <span class="mgr-comp-employee-department">BARISTA</span>
            <span class="mgr-comp-employee-department">POS MGMT.</span>
            <div class="mgr-comp-employee-contact">
              <div class="mgr-comp-contact-item">
                <i class="fas fa-building"></i>
                <span>WVSU Binhi-TBI</span>
              </div>
              <div class="mgr-comp-contact-item">
                <i class="fas fa-coffee"></i>
                <span>Paul Kaldi Coffee</span>
              </div>
            </div>
          </div>

          <div class="mgr-comp-employee-card" data-department="BARISTA,POS MGMT.">
            <h3 class="mgr-comp-employee-name">Stewart Sean Daylo</h3>
            <p class="mgr-comp-employee-title">Barista | POS Manager</p>
            <span class="mgr-comp-employee-department">BARISTA</span>
            <span class="mgr-comp-employee-department">POS MGMT.</span>
            <div class="mgr-comp-employee-contact">
              <div class="mgr-comp-contact-item">
                <i class="fas fa-building"></i>
                <span>WVSU Binhi-TBI</span>
              </div>
              <div class="mgr-comp-contact-item">
                <i class="fas fa-coffee"></i>
                <span>Paul Kaldi Coffee</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="container-fluid px-3">
        <div class="card mb-4">
          <div class="card-header">
            <h2>Business Staffs & Employees</h2>
          </div>
          <div class="card-body">
            <table id="customTable" class="table table-light">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Position</th>
                  <th>Branch</th>
                  <th>Department</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Mr. Peter Paul Dayanan</td>
                  <td>CEO & Founder</td>
                  <td>WVSU Binhi-TBI</td>
                  <td>Paul Kaldi Coffee</td>
                </tr>
                <tr>
                  <td>Ms. Glenda Baquiriza</td>
                  <td>Branch Manager in Training / Barista & POS Manager</td>
                  <td>WVSU Binhi-TBI</td>
                  <td>Coffee Roastery & Shop</td>
                </tr>
                <tr>
                  <td>Marvin John Ramos</td>
                  <td>Barista & POS Manager</td>
                  <td>WVSU Binhi-TBI</td>
                  <td>Coffee Roastery & Shop</td>
                </tr>
                <tr>
                  <td>Maricel Torremucha</td>
                  <td>Barista & POS Manager</td>
                  <td>WVSU Binhi-TBI</td>
                  <td>Coffee Roastery & Shop</td>
                </tr>
                <tr>
                  <td>Jeric Palermo</td>
                  <td>Barista & POS Manager</td>
                  <td>WVSU Binhi-TBI</td>
                  <td>Coffee Roastery & Shop</td>
                </tr>
                <tr>
                  <td>Stewart Sean Daylo</td>
                  <td>Barista & POS Manager</td>
                  <td>WVSU Binhi-TBI</td>
                  <td>Coffee Roastery & Shop</td>
                </tr>
                <?php foreach ($allUsers as $user): ?>
                  <tr>
                    <td><?= htmlspecialchars($user['full_name']) ?></td>
                    <td><?= htmlspecialchars($user['job_title'] ?? 'N/A') ?></td>
                    <td>WVSU Binhi-TBI</td>
                    <td>Coffee Roastery & Shop</td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  </div>












  <script>

    const searchBox = document.querySelector('.mgr-comp-search-box');
    const departmentFilter = document.querySelector('.mgr-comp-department-filter');
    const employeeCards = document.querySelectorAll('.mgr-comp-employee-card');

    function filterEmployees() {
      const searchTerm = searchBox.value.toLowerCase();
      const department = departmentFilter.value;

      employeeCards.forEach(card => {
        const name = card.querySelector('.mgr-comp-employee-name').textContent.toLowerCase();
        const title = card.querySelector('.mgr-comp-employee-title').textContent.toLowerCase();
        const departmentSpans = card.querySelectorAll('.mgr-comp-employee-department');


        const departments = Array.from(departmentSpans).map(span => span.textContent);

        const matchesSearch = name.includes(searchTerm) || title.includes(searchTerm);
        const matchesDepartment = department === '' || departments.includes(department);

        if (matchesSearch && matchesDepartment) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    }

    searchBox.addEventListener('input', filterEmployees);
    departmentFilter.addEventListener('change', filterEmployees);

    filterEmployees();
  </script>




  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function () {
      const pinnedName = "Mr. Peter Paul Dayanan";

      const table = $('#customTable').DataTable({
        paging: true,
        searching: true,
        lengthChange: true,
        info: true,
        ordering: true,
        pageLength: 10,
        lengthMenu: [5, 10, 20],
        language: {
          search: "Search:",
          lengthMenu: "_MENU_ entries per page"
        },
        rowCallback: function (row, data) {
          if (data[0] === pinnedName) {

            $(row).addClass('pinned-row');
          }
        },
        drawCallback: function (settings) {
          const api = this.api();


          const pinnedRows = api.rows('.pinned-row', { search: 'applied' }).nodes();
          if (pinnedRows.length) {
            $(pinnedRows).prependTo(api.table().body());
          }
        }
      });
    });
  </script>
</body>

</html>