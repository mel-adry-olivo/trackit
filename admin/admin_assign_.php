<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Assign Task</title>
  <link rel="stylesheet" href="assets/css/admin_styles.css" />
</head>
<body class="manager">


<div class="mgr-home-overlay" style="background: url('/TestPK/assets/img/banners/14.jpg') no-repeat center center / cover;">
  <div class="mgr-home-content">
    <div class="mgr-home-tag">TrackIT Admin Control</div>
    <h2 class="mgr-home-title">Assign Tasks Seamlessly.</h2>
    <p class="mgr-home-description">
      Streamline operations by delegating tasks to managers with clarity and control—ensuring priorities are met and performance stays on track.
    </p>
    <button class="mgr-home-btn" onclick="startOverlayAnimation()">Assign Tasks</button>
  </div>

  <div class="mgr-home-mission-box">
    <h4 class="mgr-home-mission-title">Admin Oversight Mission</h4>
    <p class="mgr-home-mission-text">
      Enable administrators to assign responsibilities effectively, support managerial performance, and maintain operational excellence across all teams.
    </p>
  </div>
</div>


  <div class="mgr-container">
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
        <li>
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
        <li class="mgr-active">
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
    <main class="mgr-main-content">
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

      <div class="mgr-search-filter">
      <input type="text" id="searchBar" placeholder="Search a task" onkeyup="searchTasks()" />

      <div class="mgr-title">
        <h1><br>Assign a Task</h1><br>
        <p>Click the "Add Task" button and start assigning tasks to your managers with ease and build virtual connection with the m!</p><br><br><br>
      </div>
      
        
      <div class="mgr-assign-mgr-filters">
        <button class="mgr-assign-btn-not-started" onclick="filterStatus('Not Started')">
          Not Started <span id="mgr-assign-count-not-started"></span>
        </button>
        <button class="mgr-assign-btn-in-progress" onclick="filterStatus('In Progress')">
          In Progress <span id="mgr-assign-count-in-progress"></span>
        </button>
        <button class="mgr-assign-btn-done" onclick="filterStatus('Done')">
          Done <span id="mgr-assign-count-done"></span>
        </button>
        <button class="mgr-assign-btn-overdue" onclick="filterStatus('Overdue')">
          Overdue <span id="mgr-assign-count-overdue"></span>
        </button>
      </div>



      <div class="mgr-title">
        <h1>Employee Task Overview</h1><br>
      </div>
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
          <tbody id="taskBody">

            <tr id="add-task-row">
              <td colspan="7" style="text-align: left;">
                <button onclick="addNewTaskRow()" class="add-task-btn">+ Add Task</button>
              </td>
            </tr>
          </tbody>
        </table>
      </section>
    </main>
  </div>

  <div id="confirmModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:999;">
  <div style="background:white; padding:20px; border-radius:10px; text-align:center; position:relative; min-width:300px;">
    <div class="confirm-modal-box"></div>  
    <button onclick="closeModal('confirmModal')" style="position:absolute; top:10px; right:10px; border:none; background:none; font-size:18px; cursor:pointer;">&times;</button>
    <br><br>
    <p>CONFIRM: Add this task?</p><br><br>
    <button id="confirmYes">Yes</button>
    <button id="confirmNo">No</button>
  </div>
</div>


<div id="confirmationModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:999;">
  <div style="background:white; padding:20px; border-radius:10px; text-align:center; position:relative; min-width:300px;">
    <div class="confirm-modal-box"></div>  
    <button onclick="closeModal('confirmationModal')" style="position:absolute; top:10px; right:10px; border:none; background:none; font-size:18px; cursor:pointer;">&times;</button>
    <br><br>
    <p>Confirm Task & Date Updates?</p><br><br>
    <button id="confirmYesBtn">YES</button>
    <button onclick="closeModal('confirmationModal')">NO</button>
  </div>
</div>




<div id="deleteModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:999;">
  <div style="background:white; padding:20px; border-radius:10px; text-align:center; position:relative; min-width:300px;">
    <div class="delete-modal-box"></div>  
      <button onclick="closeModal('deleteModal')" style="position:absolute; top:10px; right:10px; border:none; background:none; font-size:18px; cursor:pointer;">&times;</button>
      <br><br><p>Are you sure you want to delete this task?</p><br><br>
        <button id="deleteConfirmBtn">YES</button>
        <button onclick="closeModal('deleteModal')">NO</button>
    </div>
  </div>
</div>


</body>
</html>

<script>
function calculateTimeline(elem) {
    const row = elem.closest("tr");
    const startInput = row.querySelector(".start-date")?.value;
    const endInput = row.querySelector(".end-date")?.value;
    const timelineCell = row.querySelector(".timeline");
    const priorityCell = row.cells[6];
    const statusCell = row.cells[5];

    priorityCell.classList.remove("priority-high", "priority-medium", "priority-low", "priority-un", "priority-invalid");

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
            priorityCell.textContent = "Invalid";
            priorityCell.classList.add("priority-invalid");
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
    { name: "Marvin John Ramos", id: "PK004E", img: "assets/img/marvin.jpg" },
    { name: "Maricel Torremucha", id: "PK002E", img: "assets/img/maricel.jpg" },
    { name: "Jeric Palermo", id: "PK003E", img: "assets/img/jeric.jpg" },
    { name: "Stewart Sean Daylo", id: "PK001E", img: "assets/img/stewart.jpg" }
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

    // Column 8: Actions
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

             
                  const tempStart = document.createElement("input");
                  tempStart.value = start;
                  tempStart.classList.add("start-date");

                  const tempEnd = document.createElement("input");
                  tempEnd.value = end;
                  tempEnd.classList.add("end-date");

                  const tempWrapper = document.createElement("tr");
                  tempWrapper.appendChild(dateCell.cloneNode(true));
                  tempWrapper.querySelector("td").appendChild(tempStart);
                  tempWrapper.querySelector("td").appendChild(tempEnd);

                  calculateTimeline(tempStart);
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

// New
// Enable Edit
function enableEdit(button) {
    const row = button.closest("tr");
    const taskCell = row.querySelector("td:nth-child(3)");
    const dateCell = row.querySelector("td:nth-child(4)");
    const timelineCell = row.querySelector(".timeline");
    const statusCell = row.querySelector("td:nth-child(6)");
    const priorityCell = row.querySelector("td:nth-child(7)");
    const actionCell = row.querySelector(".action-cell");

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

    // Add event listeners for date changes
    const startDateInput = dateCell.querySelector(".start-date");
    const endDateInput = dateCell.querySelector(".end-date");
    startDateInput.addEventListener("change", () => calculateTimeline(startDateInput));
    endDateInput.addEventListener("change", () => calculateTimeline(endDateInput));

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
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            // Update the task cell
            taskCell.textContent = newTask;

            // Update the date cell with the new dates
            dateCell.innerHTML = `
                <div><strong>Start:</strong> ${startDate}</div>
                <div><strong>End:</strong> ${endDate}</div>
            `;


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


</body>
</html>