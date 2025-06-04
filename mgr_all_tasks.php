<?php

include "database/queries.php";

session_start();

$allTasks = getManagerAllTasks($_SESSION['uid']);


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>All Tasks</title>
  <script src="./assets/js/main/clock.js" defer></script>
  <link rel="stylesheet" h <link rel="stylesheet" href="assets/css/styles.css" />
</head>

<body class="manager">


  <div class="top-banner" style="
  background: url('./assets/img/banners/25.jpg') no-repeat center center / cover;
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


      <div class="mgr-search-filter">
        <input type="text" id="searchBar" placeholder="Search a task" onkeyup="searchTasks()" />

        <div class="mgr-title">
          <h1><br>All Employee Tasks</h1><br>
          <p>Refer here to view real-time tasks.</p><br><br><br>
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
                <th>TASK</th>
                <th>START DATE</th>
                <th>END DATE</th>
                <th>TIMELINE</th>
                <th>STATUS</th>
                <th>PRIORITY</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody id="taskBody">
              <?php foreach ($allTasks as $task): ?>
                <tr>
                  <td><?= $task['assignee_name'] ?></td>
                  <td><?= $task['assignee_id'] ?></td>
                  <td><?= $task['task'] ?></td>
                  <td><?= $task['start_date'] ?></td>
                  <td><?= $task['end_date'] ?></td>
                  <td><?= $task['timeline'] ?></td>
                  <td><?= $task['status'] ?></td>
                  <td><?= $task['priority'] ?></td>
                  <td>
                    <button onclick="editTask(<?= $task['id'] ?>)">Edit</button>
                    <button onclick="confirmDelete(<?= $task['id'] ?>)">Delete</button>
                  </td>
                </tr>
              <?php endforeach; ?>
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



</script>
</body>

</html>