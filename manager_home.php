<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manager Dashboard</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <script src="./assets/js/main/clock.js" defer></script>
</head>

<body class="manager">
  <div class="mgr-home-overlay" style="background: url('./assets/img/manager-db.jpg') no-repeat center center / cover;">
    <div class="mgr-home-content">
      <div class="mgr-home-tag">Paul Kaldi Manager Portal</div>
      <h2 class="mgr-home-title">Lead Your Café Crew with Confidence.</h2>
      <p class="mgr-home-description">
        From espresso pulls to staff scheduling, manage every detail of your coffee shop’s daily grind with ease and
        efficiency.
      </p>
      <button class="mgr-home-btn" onclick="startOverlayAnimation()">Get Started</button>
    </div>

    <div class="mgr-home-mission-box">
      <h4 class="mgr-home-mission-title">Our Mission</h4>
      <p class="mgr-home-mission-text">
        Empower coffee shop managers to build passionate, high-performing teams who brew consistency, care, and customer
        satisfaction every day.
      </p>
    </div>
  </div>

  <div class="mgr-container" id="main-dashboard">

    <!-- Sidebar -->
    <?php include "./php/includes/mgr_sidebar.php"; ?>

    <!-- Main Content -->


    <main class="mgr-main-content ">
      <header class="mgr-header">

        <div class="calendar">
          <div class="month-year" id="month-year">
            <?php echo date('F Y'); ?>
          </div>
          <div class="calendar-grid">
            <div class="day-names" id="day-names">
              <?php
              $dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
              foreach ($dayNames as $day) {
                echo '<div class="day-name">' . $day . '</div>';
              }
              ?>
            </div>
            <div class="dates" id="dates">
              <?php
              $today = new DateTime();
              $currentDate = (int) $today->format('d');
              $currentMonth = (int) $today->format('m') - 1;
              $currentYear = (int) $today->format('Y');
              $currentDayOfWeek = (int) $today->format('w');

              $startOffset = $currentDayOfWeek - 3;

              for ($i = 0; $i < 7; $i++) {
                $date = new DateTime();
                $date->setDate($currentYear, $currentMonth + 1, $currentDate + $startOffset + $i);
                $dayClass = 'day';
                if ($date->format('Y-m-d') === $today->format('Y-m-d')) {
                  $dayClass .= ' today';
                }
                echo '<div class="' . $dayClass . '">' . $date->format('j') . '</div>';
              }
              ?>
            </div>
          </div>
        </div>

        <div>
          <h1>Welcome, Glenda</h1>
          <p class="mgr-id"><b>Branch Manager </b> || ID No.: PKM001</p>
        </div>
        <div class="mgr-branch-info">
          <span>Branch: <strong>WVSU-BINHI TBI</strong> | Time: </span>
          <span id="clock">--:--:--</span>
        </div>
      </header>

      <div class="mgr-search-filter">
        <input type="text" id="searchBar" placeholder="Search a task" onkeyup="searchTasks()" />

        <div class="mgr-filters">
          <button class="btn-not-started" onclick="filterStatus('Not Started')">
            Not Started <span id="count-not-started"></span>
          </button>
          <button class="btn-in-progress" onclick="filterStatus('In Progress')">
            In Progress <span id="count-in-progress"></span>
          </button>
          <button class="btn-done" onclick="filterStatus('Done')">
            Done <span id="count-done"></span>
          </button>
          <button class="btn-overdue" onclick="filterStatus('Overdue')">
            Overdue <span id="count-overdue"></span>
          </button>

          <a href="mgr_rank.php" class="link-block">
            <div class="leaderboard-container">
              <div class="leaderboard-header">
                <h2>Current Leaderboard</h2>
                <p>Monthly Performance Rankings</p>
              </div>

              <div class="compact-leaderboard">
                <!-- Rank 1 -->
                <div class="leaderboard-item rank-1" onclick="window.location.href='employee_profile.php?id=0001'">
                  <span class="rank-number">1</span>
                  <span class="employee-name">Jeric Palermo</span>
                  <span class="rank-score"> pts</span>
                </div>

                <!-- Rank 2 -->
                <div class="leaderboard-item rank-2" onclick="window.location.href='employee_profile.php?id=0002'">
                  <span class="rank-number">2</span>
                  <span class="employee-name">Stewart Sean Daylo</span>
                  <span class="rank-score"> pts</span>
                </div>

                <!-- Rank 3 -->
                <div class="leaderboard-item rank-3" onclick="window.location.href='employee_profile.php?id=0003'">
                  <span class="rank-number">3</span>
                  <span class="employee-name">Marvin John Ramos</span>
                  <span class="rank-score"> pts</span>
                </div>
              </div>

            </div>
        </div>
      </div>

      <section class="mgr-section">
        <a href="mgr_all_tasks.php" class="link-block">
          <div class="mgr-title">
            <br><br>
            <h1>Employee Task Overview</h1><br>
          </div>
        </a>

        <section class="mgr-task-table">
          <table>
            <thead>
              <tr>
                <th>ASSIGNEE</th>
                <th>ID NO.</th>
                <th>TASKS</th>
                <th>DATE</th>
                <th>TIMELINE</th>
                <th>STATUS</th>
                <th>PRIORITY</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
          </table>
        </section>
        <a href="./mgr_comp.php" class="link-block">
          <div class="mgr-title">
            <br><br>
            <h1>Company</h1><br>
          </div>
        </a>
        <a href="mgr_comp.php" class="link-block">
          <div class="container-fluid px-3">
            <div class="card mb-4">
              <div class="card-header">
                <h2>Business Staffs & Employees</h2>
              </div>
        </a>
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
            </tbody>
          </table>
        </div>
  </div>
  <script>
    function calculateTimeline(elem) {
      const row = elem.closest("tr");
      const startInput = row.querySelector(".start-date").value;
      const endInput = row.querySelector(".end-date").value;
      const timelineCell = row.querySelector(".timeline");
      const priorityCell = row.cells[6];
      const statusCell = row.cells[5];


      priorityCell.classList.remove("priority-high", "priority-medium", "priority-low");

      if (startInput && endInput) {
        const startDate = new Date(startInput);
        const endDate = new Date(endInput);
        const diffTime = endDate - startDate;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

        if (diffDays > 0) {
          timelineCell.textContent = `${diffDays} day${diffDays > 1 ? 's' : ''}`;


          statusCell.textContent = "Not Started";

          if (diffDays <= 3) {
            priorityCell.textContent = "High";
            priorityCell.classList.add("priority-high");
          } else if (diffDays <= 7) {
            priorityCell.textContent = "Medium";
            priorityCell.classList.add("priority-medium");
          } else if (diffDays <= 100) {
            priorityCell.textContent = "Low";
            priorityCell.classList.add("priority-low");
          } else {
            priorityCell.textContent = "Undefined";
            priorityCell.classList.add("priority-un");
          }
        } else {
          timelineCell.textContent = "Invalid";
          priorityCell.classList.add("priority-invalid");
          priorityCell.textContent = "Invalid";
          statusCell.textContent = "Invalid";
        }
      } else {
        timelineCell.textContent = "-";
        priorityCell.textContent = "";
        statusCell.textContent = "";
      }
    }


    function searchTasks() {
      const input = document.getElementById("searchBar").value.toLowerCase();
      const rows = document.querySelectorAll("#taskBody tr");

      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
      });
    }


    function updateClock() {
      const now = new Date();
      document.getElementById("clock").textContent = now.toLocaleTimeString();
    }
    setInterval(updateClock, 1000);
    updateClock();


    function filterStatus(status) {
      const rows = document.querySelectorAll("#taskBody tr");
      rows.forEach(row => {
        const rowStatus = row.cells[5].innerText.trim();
        row.style.display = rowStatus === status || status === 'All' ? '' : 'none';
      });
    }

    function updateStatusCounts() {
      const rows = document.querySelectorAll("#taskBody tr");
      const today = new Date();
      const counts = {
        "Not Started": 0,
        "In Progress": 0,
        "Done": 0,
        "Overdue": 0
      };

      rows.forEach(row => {
        const endDateInput = row.querySelector(".end-date");
        const statusCell = row.querySelector("td:nth-child(6)");
        const endDateValue = endDateInput.value;

        if (!endDateValue) {
          statusCell.textContent = "Not Started";
          counts["Not Started"]++;
          return;
        }

        const endDate = new Date(endDateValue);
        endDate.setHours(0, 0, 0, 0);
        today.setHours(0, 0, 0, 0);

        if (endDate < today) {
          statusCell.textContent = "Overdue";
          counts["Overdue"]++;
        } else if (endDate.getTime() === today.getTime()) {
          statusCell.textContent = "In Progress";
          counts["In Progress"]++;
        } else {
          statusCell.textContent = "Not Started";
          counts["Not Started"]++;
        }
      });

      document.getElementById("count-not-started").textContent = counts["Not Started"];
      document.getElementById("count-in-progress").textContent = counts["In Progress"];
      document.getElementById("count-done").textContent = counts["Done"];
      document.getElementById("count-overdue").textContent = counts["Overdue"];
    }

    const employees = [
      { name: "Marvin John Ramos", id: "004", img: "assets/img/marvin.jpg" },
      { name: "Maricel Torremucha", id: "002", img: "assets/img/maricel.jpg" },
      { name: "Jeric Palermo", id: "003", img: "assets/img/jeric.jpg" },
      { name: "Stewart Sean Daylo", id: "001", img: "assets/img/stewart.jpg" }
    ];

    const tasks = [
      "Staff: Daily Cleaning - Counters", "Staff: Daily Cleaning - Sinks", "Staff: Daily Cleaning - Trash Cans", "Staff: Daily Cleaning - Floor",
      "Staff: Daily Cleaning - Windows", "Staff: Daily Cleaning - Espresso Machine", "Staff: Daily Cleaning - Grinder", "Staff: Daily Cleaning - Milk Jugs",
      "Prepare Ingredients & Supplies", "Shop Opening",
      "POS Manager: Receive Order Details", "POS Manager: Receive Payment", "POS Manager: Input order details in Loyverse POS App",
      "POS Manager: Print order receipt", "POS Manager: Send the receipt for reference to the Barista",
      "Write the Petty Cash", "Take Payments", "List down the Delivery Fees",
      "Barista: Prepare ingredients", "Barista: Grind & Roast beans", "Cleaning", "Check Inventory", "Product Delivery"
    ];

    document.getElementById("addTaskBtn").addEventListener("click", addNewTaskRow);

    function addNewTaskRow() {
      const tableBody = document.getElementById("taskBody");
      const addTaskRow = document.getElementById("add-task-row");
      const newRow = document.createElement("tr");

      // Column 1: Assignee
      const assigneeCell = document.createElement("td");
      const assigneeSelect = document.createElement("select");
      assigneeSelect.classList.add("assignee-select");
      assigneeSelect.innerHTML = `<option value="" disabled selected>Select Employee</option>`;
      employees.forEach(emp => {
        const option = document.createElement("option");
        option.value = emp.id;
        option.textContent = emp.name;
        assigneeSelect.appendChild(option);
      });
      assigneeCell.appendChild(assigneeSelect);
      newRow.appendChild(assigneeCell);

      // Column 2: Employee ID
      const idCell = document.createElement("td");
      idCell.classList.add("employee-id");
      idCell.textContent = "-";
      newRow.appendChild(idCell);

      // Column 3: Task
      const taskCell = document.createElement("td");
      const taskSelect = document.createElement("select");
      taskSelect.classList.add("task-select");
      taskSelect.innerHTML = `<option value="" disabled selected>Select Task</option>`;
      tasks.forEach(task => {
        const option = document.createElement("option");
        option.value = task;
        option.textContent = task;
        taskSelect.appendChild(option);
      });
      taskCell.appendChild(taskSelect);
      newRow.appendChild(taskCell);

      // Column 4: Date
      const dateCell = document.createElement("td");
      const startDateInput = document.createElement("input");
      startDateInput.type = "date";
      startDateInput.classList.add("start-date");
      startDateInput.addEventListener("change", function () {
        calculateTimeline(this);
      });

      const endDateInput = document.createElement("input");
      endDateInput.type = "date";
      endDateInput.classList.add("end-date");
      endDateInput.addEventListener("change", function () {
        calculateTimeline(this);
      });

      dateCell.appendChild(startDateInput);
      dateCell.appendChild(document.createTextNode(" to "));
      dateCell.appendChild(endDateInput);
      newRow.appendChild(dateCell);

      // Column 5: Timeline
      const timelineCell = document.createElement("td");
      timelineCell.classList.add("timeline");
      timelineCell.textContent = "-";
      newRow.appendChild(timelineCell);

      // Column 6: Status
      const statusCell = document.createElement("td");
      statusCell.textContent = "-";
      newRow.appendChild(statusCell);

      // Column 7: Priority
      const priorityCell = document.createElement("td");
      priorityCell.classList.add("stat");
      priorityCell.textContent = "-";
      newRow.appendChild(priorityCell);

      //Column 8: Actions
      const actionCell = document.createElement("td");
      actionCell.classList.add("action-cell");
      actionCell.innerHTML = `
      <button class="editBtn" onclick="enableEdit(this)">Edit</button>
      <button class="deleteBtn" onclick="deleteRow(this)">Delete</button>
    `;
      newRow.appendChild(actionCell);


      tableBody.insertBefore(newRow, addTaskRow);

      // On employee selection
      assigneeSelect.addEventListener("change", function () {
        const selectedId = this.value;
        const selectedEmployee = employees.find(emp => emp.id === selectedId);
        if (selectedEmployee) {
          idCell.textContent = selectedEmployee.id;
          const profileContainer = document.createElement("div");
          profileContainer.style.display = "flex";
          profileContainer.style.alignItems = "center";
          profileContainer.innerHTML = `
          <img src="${selectedEmployee.img}" alt="${selectedEmployee.name}" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 8px;">
          <span>${selectedEmployee.name}</span>
        `;
          assigneeCell.innerHTML = "";
          assigneeCell.appendChild(profileContainer);
        }
      });

      // On task selection
      taskSelect.addEventListener("change", function () {
        const selectedTask = this.value;
        const modal = document.getElementById("confirmModal");
        modal.querySelector("p").textContent = "Confirm Add This Task?";
        modal.style.display = "flex";

        document.getElementById("confirmYes").onclick = () => {
          modal.style.display = "none";

          taskCell.textContent = selectedTask;


          function checkDatesAndPrompt() {
            const start = startDateInput.value;
            const end = endDateInput.value;

            if (start && end) {
              const dateModal = document.getElementById("confirmationModal");
              dateModal.querySelector("p").textContent = "Confirm Task & Date Updates?";
              dateModal.style.display = "flex";

              document.getElementById("confirmYesBtn").onclick = () => {
                dateModal.style.display = "none";


                dateCell.innerHTML = `
                <div><strong>Start:</strong> ${start}</div>
                <div><strong>End:</strong> ${end}</div>
              `;

                calculateTimeline(dateCell);
              };

              document.getElementById("confirmNo").onclick = () => {
                dateModal.style.display = "none";
                startDateInput.value = "";
                endDateInput.value = "";
              };
            }
          }

          startDateInput.addEventListener("change", checkDatesAndPrompt);
          endDateInput.addEventListener("change", checkDatesAndPrompt);
        };

        document.getElementById("confirmNo").onclick = () => {
          modal.style.display = "none";
          taskSelect.value = "";
        };
      });
    }




    //New
    //Enable Edit
    function enableEdit(button) {
      const row = button.closest("tr");
      const taskCell = row.querySelector("td:nth-child(3)");
      const dateCell = row.querySelector("td:nth-child(4)");
      const timelineCell = row.querySelector(".timeline");
      const statusCell = row.querySelector("td:nth-child(6)");
      const priorityCell = row.querySelector("td:nth-child(7)");
      const actionCell = row.querySelector(".action-cell");

      // Get current values
      const originalTask = taskCell.textContent.trim();
      const startDateText = dateCell.querySelector("div:nth-child(1)")?.textContent.replace("Start:", "").trim();
      const endDateText = dateCell.querySelector("div:nth-child(2)")?.textContent.replace("End:", "").trim();

      // Replace task with dropdown
      taskCell.innerHTML = createTaskDropdown();
      const taskSelect = taskCell.querySelector("select");
      taskSelect.value = originalTask;

      // Replace dates with input fields
      dateCell.innerHTML = `
    <input type="date" class="start-date" value="${startDateText || ""}">
    to
    <input type="date" class="end-date" value="${endDateText || ""}">
  `;

      // Change "Edit" to "Confirm"
      button.textContent = "Confirm";
      button.onclick = function () {
        // Show confirmation modal
        const modal = document.getElementById("confirmationModal");
        modal.querySelector("p").textContent = "CONFIRM TASK & DATE UPDATES?";
        modal.style.display = "flex";

        // Handle YES
        document.getElementById("confirmBtn").onclick = function () {
          const newTask = taskSelect.value;
          const startDate = row.querySelector(".start-date").value;
          const endDate = row.querySelector(".end-date").value;

          // Make static
          taskCell.textContent = newTask;
          dateCell.innerHTML = `
        <div><strong>Start:</strong> ${startDate}</div>
        <div><strong>End:</strong> ${endDate}</div>
      `;

          // Recalculate timeline, status, priority
          calculateTimeline(dateCell);

          // Reset "Confirm" to "Edit"
          button.textContent = "Edit";
          button.onclick = function () {
            enableEdit(button);
          };

          modal.style.display = "none";
        };

        // Handle NO
        document.getElementById("cancelBtn").onclick = function () {
          modal.style.display = "none";
        };
      };
    }

    function createTaskDropdown() {
      return `<select class="task-dropdown">${tasks.map(task => `<option value="${task}">${task}</option>`).join("")}</select>`;
    }

    function toggleActive(card) {
      document.querySelectorAll(".home-rank-card").forEach(el => {
        el.classList.remove("active");
      });
      card.classList.add("active");
    }


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

  <script>
    function startOverlayAnimation() {
      const overlay = document.querySelector('.mgr-home-overlay');
      const button = document.querySelector('.mgr-home-btn');

      if (overlay.classList.contains('slide-up')) {

        overlay.classList.remove('slide-up');
        button.textContent = "Get Started";
        window.scrollTo({ top: 0, behavior: 'smooth' });
      } else {

        overlay.classList.add('slide-up');
        button.textContent = "Hey there!";
        document.getElementById('main-dashboard').scrollIntoView({ behavior: 'smooth' });
      }
    }

  </script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</body>

</html>