<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Assign Task</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
</head>

<body class="manager">


  <div class="mgr-home-overlay" style="background: url('./assets/img/banners/13.jpg') no-repeat center center / cover;">
    <div class="mgr-home-content">
      <div class="mgr-home-tag">TrackIT Task Management</div>
      <h2 class="mgr-home-title">Delegate Tasks with Precision.</h2>
      <p class="mgr-home-description">
        From daily assignments to priority projects, efficiently distribute workloads and track completion across your
        entire team.
      </p>
      <button class="mgr-home-btn" onclick="startOverlayAnimation()">Assign Tasks</button>
    </div>

    <div class="mgr-home-mission-box">
      <h4 class="mgr-home-mission-title">Task Management Mission</h4>
      <p class="mgr-home-mission-text">
        Empower managers to create balanced workloads, clear expectations, and accountable teams through transparent
        task delegation.
      </p>
    </div>
  </div>

  <div class="mgr-container">
    <!-- Sidebar -->
    <?php include "./php/includes/mgr_sidebar.php"; ?>


    <!-- Main Content -->
    <main class="mgr-main-content">
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

      <div class="mgr-search-filter">
        <input type="text" id="searchBar" placeholder="Search a task" onkeyup="searchTasks()" />

        <div class="mgr-title">
          <h1><br>Assign a Task</h1><br>
          <p>Click the "Add Task" button and start assigning tasks to your employees with ease and build virtual
            connection with them!</p><br><br><br>
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

  <div id="confirmModal"
    style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:999;">
    <div
      style="background:white; padding:20px; border-radius:10px; text-align:center; position:relative; min-width:300px;">
      <div class="confirm-modal-box"></div>
      <button onclick="closeModal('confirmModal')"
        style="position:absolute; top:10px; right:10px; border:none; background:none; font-size:18px; cursor:pointer;">&times;</button>
      <br><br>
      <p>CONFIRM: Add this task?</p><br><br>
      <button id="confirmYes">Yes</button>
      <button id="confirmNo">No</button>
    </div>
  </div>


  <div id="confirmationModal"
    style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:999;">
    <div
      style="background:white; padding:20px; border-radius:10px; text-align:center; position:relative; min-width:300px;">
      <div class="confirm-modal-box"></div>
      <button onclick="closeModal('confirmationModal')"
        style="position:absolute; top:10px; right:10px; border:none; background:none; font-size:18px; cursor:pointer;">&times;</button>
      <br><br>
      <p>Confirm Task & Date Updates?</p><br><br>
      <button id="confirmYesBtn">YES</button>
      <button onclick="closeModal('confirmationModal')">NO</button>
    </div>
  </div>




  <div id="deleteModal"
    style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:999;">
    <div
      style="background:white; padding:20px; border-radius:10px; text-align:center; position:relative; min-width:300px;">
      <div class="delete-modal-box"></div>
      <button onclick="closeModal('deleteModal')"
        style="position:absolute; top:10px; right:10px; border:none; background:none; font-size:18px; cursor:pointer;">&times;</button>
      <br><br>
      <p>Are you sure you want to delete this task?</p><br><br>
      <button id="deleteConfirmBtn">YES</button>
      <button onclick="closeModal('deleteModal')">NO</button>
    </div>
  </div>
  </div>


</body>

</html>

<script>
  // Function to calculate timeline and priority based on dates
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

  // Search functionality
  function searchTasks() {
    const input = document.getElementById("searchBar").value.toLowerCase();
    const rows = document.querySelectorAll("#taskBody tr:not(#add-task-row)");

    rows.forEach(row => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(input) ? "" : "none";
    });
  }

  // Real-time clock
  function updateClock() {
    const now = new Date();
    document.getElementById("clock").textContent = now.toLocaleTimeString();
  }
  setInterval(updateClock, 1000);
  updateClock();

  // Filter tasks by status
  function filterStatus(status) {
    const rows = document.querySelectorAll("#taskBody tr:not(#add-task-row)");
    rows.forEach(row => {
      const rowStatus = row.cells[5].innerText.trim();
      row.style.display = rowStatus === status || status === 'All' ? '' : 'none';
    });
  }

  // Update status counts
  function updateStatusCounts() {
    const rows = document.querySelectorAll("#taskBody tr:not(#add-task-row)");
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
      const endDateValue = endDateInput ? endDateInput.value : null;

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

    document.getElementById("mgr-assign-count-not-started").textContent = counts["Not Started"];
    document.getElementById("mgr-assign-count-in-progress").textContent = counts["In Progress"];
    document.getElementById("mgr-assign-count-done").textContent = counts["Done"];
    document.getElementById("mgr-assign-count-overdue").textContent = counts["Overdue"];
  }

  // Employee and task data
  const employees = [
    { name: "Marvin John Ramos", id: "PK004E", img: "assets/img/marvin.jpg" },
    { name: "Maricel Torremucha", id: "PK002E", img: "assets/img/maricel.jpg" },
    { name: "Jeric Palermo", id: "PK003E", img: "assets/img/jeric.jpg" },
    { name: "Stewart Sean Daylo", id: "PK001E", img: "assets/img/stewart.jpg" }
  ];

  const tasks = [
    // === INVENTORY & SUPPLIES (Weekly/Monthly) ===
    "Full Inventory Audit (Count all stock, check expiry dates)",
    "Reorganize Storage Room (Label shelves, FIFO system)",
    "Compare Supplier Prices & Negotiate Discounts",
    "Place Monthly Bulk Order (Coffee, cups, syrups)",

    // === EQUIPMENT MAINTENANCE (Weekly) ===
    "Deep Clean & Descale Espresso Machine (Full disassembly)",
    "Service Coffee Grinders (Replace burrs, calibrate)",
    "Clean Refrigerator Coils & Check Seals",

    // === MENU & QUALITY (Monthly) ===
    "Test & Introduce 1 New Seasonal Drink",
    "Update Menu Board/Printed Menus",
    "Conduct Staff Coffee Tasting (Train on new beans)",

    // === FACILITY UPKEEP (Monthly) ===
    "Deep Clean Floor Grout & Drains",
    "Repaint Chipped Walls/Furniture",
    "Reorganize Café Layout for Efficiency",

    // === ADMIN & TRAINING (Ongoing) ===
    "Create/Update Training Manual (Recipes, POS steps)",
    "Analyze 1 Month of Sales Data (Identify waste/low sellers)",
    "Plan a Staff Appreciation Day (Budget, activities)"
  ];

  // Modal functions
  function showModal(modalId, message) {
    const modal = document.getElementById(modalId);
    modal.querySelector("p").textContent = message;
    modal.style.display = "flex";
    return modal;
  }

  function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
  }

  // Add new task row
  function addNewTaskRow() {
    const tableBody = document.getElementById("taskBody");
    const addTaskRow = document.getElementById("add-task-row");
    const newRow = document.createElement("tr");
    newRow.dataset.isNew = "true";

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
        <button class="saveBtn" onclick="saveTask(this)">Save</button>
        <button class="cancelBtn" onclick="cancelTask(this)">Cancel</button>
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
      calculateTimeline(this);
    });
  }

  // Save task to database
  function saveTask(button) {
    const row = button.closest("tr");
    const isNew = row.dataset.isNew === "true";
    const employeeId = row.querySelector(".employee-id").textContent;
    const task = row.querySelector(".task-select").value;
    const startDate = row.querySelector(".start-date").value;
    const endDate = row.querySelector(".end-date").value;

    if (!employeeId || employeeId === "-" || !task || !startDate || !endDate) {
      alert("Please fill all required fields");
      return;
    }

    const formData = {
      employee_id: employeeId,
      task: task,
      start_date: startDate,
      end_date: endDate
    };

    const modal = showModal("confirmModal", "CONFIRM: Add this task?");

    document.getElementById("confirmYes").onclick = function () {
      closeModal("confirmModal");

      fetch('task_actions.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
          action: 'add_task',
          ...formData
        })
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            row.dataset.taskId = data.task_id;
            row.dataset.isNew = "false";
            row.querySelector(".saveBtn").textContent = "Edit";
            row.querySelector(".saveBtn").onclick = function () { enableEdit(this); };
            row.querySelector(".cancelBtn").textContent = "Delete";
            row.querySelector(".cancelBtn").onclick = function () { deleteTask(this); };
            updateStatusCounts();
            alert("Task added successfully");
          } else {
            alert("Error adding task: " + (data.message || "Unknown error"));
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert("Error adding task");
        });
    };

    document.getElementById("confirmNo").onclick = function () {
      closeModal("confirmModal");
    };
  }

  // Enable edit mode for existing task
  function enableEdit(button) {
    const row = button.closest("tr");
    const taskId = row.dataset.taskId;
    const taskCell = row.querySelector("td:nth-child(3)");
    const dateCell = row.querySelector("td:nth-child(4)");
    const statusCell = row.querySelector("td:nth-child(6)");
    const actionCell = row.querySelector(".action-cell");

    const originalTask = taskCell.textContent.trim();
    const dateText = dateCell.innerHTML;
    const dateRegex = /<strong>Start:<\/strong> ([\d-]+).*<strong>End:<\/strong> ([\d-]+)/;
    const dateMatches = dateText.match(dateRegex);
    const startDate = dateMatches ? dateMatches[1] : "";
    const endDate = dateMatches ? dateMatches[2] : "";

    // Replace task with dropdown
    taskCell.innerHTML = createTaskDropdown();
    const taskSelect = taskCell.querySelector("select");
    taskSelect.value = originalTask;

    // Replace dates with input fields
    dateCell.innerHTML = `
        <input type="date" class="start-date" value="${startDate || ""}">
        to
        <input type="date" class="end-date" value="${endDate || ""}">
    `;

    // Add event listeners for date changes
    const startDateInput = dateCell.querySelector(".start-date");
    const endDateInput = dateCell.querySelector(".end-date");
    startDateInput.addEventListener("change", () => calculateTimeline(startDateInput));
    endDateInput.addEventListener("change", () => calculateTimeline(endDateInput));

    // Change buttons
    button.textContent = "Save";
    button.onclick = function () { saveEdit(this); };
    actionCell.querySelector(".cancelBtn").textContent = "Cancel";
    actionCell.querySelector(".cancelBtn").onclick = function () { cancelEdit(this); };
  }

  // Save edited task
  function saveEdit(button) {
    const row = button.closest("tr");
    const taskId = row.dataset.taskId;
    const employeeId = row.querySelector(".employee-id").textContent;
    const task = row.querySelector(".task-select").value;
    const startDate = row.querySelector(".start-date").value;
    const endDate = row.querySelector(".end-date").value;

    if (!employeeId || !task || !startDate || !endDate) {
      alert("Please fill all required fields");
      return;
    }

    const modal = showModal("confirmationModal", "Confirm Task & Date Updates?");

    document.getElementById("confirmYesBtn").onclick = function () {
      closeModal("confirmationModal");

      fetch('task_actions.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
          action: 'edit_task',
          task_id: taskId,
          employee_id: employeeId,
          task: task,
          start_date: startDate,
          end_date: endDate
        })
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Update the row with the new values
            row.querySelector("td:nth-child(3)").textContent = task;
            row.querySelector("td:nth-child(4)").innerHTML = `
                    <div><strong>Start:</strong> ${startDate}</div>
                    <div><strong>End:</strong> ${endDate}</div>
                `;
            calculateTimeline(row.querySelector(".start-date"));

            // Reset buttons
            button.textContent = "Edit";
            button.onclick = function () { enableEdit(this); };
            row.querySelector(".cancelBtn").textContent = "Delete";
            row.querySelector(".cancelBtn").onclick = function () { deleteTask(this); };

            updateStatusCounts();
            alert("Task updated successfully");
          } else {
            alert("Error updating task: " + (data.message || "Unknown error"));
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert("Error updating task");
        });
    };
  }

  // Cancel edit mode
  function cancelEdit(button) {
    const row = button.closest("tr");
    const taskId = row.dataset.taskId;

    if (confirm("Discard changes?")) {
      // Reload the row data from the server or keep original values
      // For simplicity, we'll just reset to view mode
      row.querySelector("td:nth-child(3)").textContent = row.dataset.originalTask || "";
      // Reset other fields as needed

      // Reset buttons
      row.querySelector(".saveBtn").textContent = "Edit";
      row.querySelector(".saveBtn").onclick = function () { enableEdit(this); };
      row.querySelector(".cancelBtn").textContent = "Delete";
      row.querySelector(".cancelBtn").onclick = function () { deleteTask(this); };
    }
  }

  // Delete task
  function deleteTask(button) {
    const row = button.closest("tr");
    const taskId = row.dataset.taskId;

    if (!taskId) {
      row.remove();
      return;
    }

    const modal = showModal("deleteModal", "Are you sure you want to delete this task?");

    document.getElementById("deleteConfirmBtn").onclick = function () {
      closeModal("deleteModal");

      fetch('task_actions.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
          action: 'delete_task',
          task_id: taskId
        })
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            row.remove();
            updateStatusCounts();
            alert("Task deleted successfully");
          } else {
            alert("Error deleting task: " + (data.message || "Unknown error"));
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert("Error deleting task");
        });
    };
  }

  // Cancel new task
  function cancelTask(button) {
    const row = button.closest("tr");
    if (confirm("Cancel this new task?")) {
      row.remove();
    }
  }

  // Helper function to create task dropdown
  function createTaskDropdown() {
    let options = '<option value="" disabled selected>Select Task</option>';
    tasks.forEach(task => {
      options += `<option value="${task}">${task}</option>`;
    });
    return `<select class="task-select">${options}</select>`;
  }

  // Overlay animation
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
      document.querySelector('.mgr-container').scrollIntoView({ behavior: 'smooth' });
    }
  }

  // Initialize the page
  document.addEventListener("DOMContentLoaded", function () {
    updateStatusCounts();
  });
</script>



</body>

</html>