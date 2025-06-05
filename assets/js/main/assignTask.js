let currentFormToSubmit = null;

const originalValues = {};

function addNewTaskRow() {
  if (document.getElementById("new-task-form-row")) return;

  const tableBody = document.getElementById("taskBody");
  const addTaskRow = document.getElementById("add-task-row");

  const newRow = document.createElement("tr");
  newRow.id = "new-task-form-row";

  newRow.innerHTML = `
        <td>
            <select name="assignee_code" class="new-task-assignee">
                <option value="">Select Assignee</option>
                ${employees
                  .map(
                    (emp) =>
                      `<option value="${emp.user_code}">${emp.full_name}</option>`
                  )
                  .join("")}
            </select>
        </td>
        <td>-</td> <td>
            <select name="template_id">
                <option value="">Select Task</option>
                ${taskTemplates
                  .map(
                    (template) =>
                      `<option value="${template.id}">${template.description}</option>`
                  )
                  .join("")}
            </select>
        </td>
        <td><input type="date" name="start_date" /></td>
        <td><input type="date" name="end_date" /></td>
        <td>-</td> <td>Not Started</td> <td>
            <select name="priority">
                <option value="Low">Low</option>
                <option value="Medium" selected>Medium</option>
                <option value="High">High</option>
            </select>
        </td>
        <td>
            <button type="button" onclick="saveNewTask()">Save</button>
            <button type="button" onclick="cancelNewTask()">Cancel</button>
        </td>
    `;

  tableBody.insertBefore(newRow, addTaskRow);

  document.querySelector(".add-task-btn").style.display = "none";
}

function cancelNewTask() {
  const newRow = document.getElementById("new-task-form-row");
  if (newRow) {
    newRow.remove();
  }
  document.querySelector(".add-task-btn").style.display = "inline-block";
}

async function saveNewTask() {
  const newRow = document.getElementById("new-task-form-row");
  const assigneeCode = newRow.querySelector('[name="assignee_code"]').value;
  const templateId = newRow.querySelector('[name="template_id"]').value;
  const startDate = newRow.querySelector('[name="start_date"]').value;
  const endDate = newRow.querySelector('[name="end_date"]').value;
  const priority = newRow.querySelector('[name="priority"]').value;

  if (!assigneeCode || !templateId || !startDate || !endDate) {
    alert(
      "Please fill out all required fields: Assignee, Task, Start Date, and End Date."
    );
    return;
  }

  const payload = new URLSearchParams();
  payload.append("action", "add_task");
  payload.append("assignee_code", assigneeCode);
  payload.append("template_id", templateId);
  payload.append("start_date", startDate);
  payload.append("end_date", endDate);
  payload.append("priority", priority);

  try {
    const resp = await fetch(window.location.href, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: payload.toString(),
    });

    const result = await resp.json();

    if (result.success) {
      cancelNewTask();
      buildNewTaskRow(result.data);
      showTemporaryMessage("Task added successfully.", "success");
    } else {
      alert(
        "Error adding task: " + (result.message || "Unknown server error.")
      );
    }
  } catch (err) {
    console.error("Save task error:", err);
    alert("An unexpected network error occurred.");
  }
}

function buildNewTaskRow(task) {
  console.log(task);
  const tableBody = document.getElementById("taskBody");
  const addTaskRow = document.getElementById("add-task-row");

  const tr = document.createElement("tr");
  tr.id = `task-row-${task.id}`;

  tr.innerHTML = `
    <td>${task.assignee_name}</td>
    <td>${task.assignee_code}</td>
    <td class="editable" data-field="task_display" data-template-id="${task.template_id}">
      ${task.task}
    </td>
    <td class="editable" data-field="start_date_display">${task.start_date}</td>
    <td class="editable" data-field="end_date_display">${task.end_date}</td>
    <td>${task.timeline}</td>
    <td>${task.status}</td>
    <td class="editable" data-field="priority_display">${task.priority}</td>
    <td>
      <!-- Hidden form fields for edit_task -->
      <input type="hidden" name="action" value="edit_task">
      <input type="hidden" name="task_id" value="${task.id}">
      <input type="hidden" name="template_id" id="template_id_hidden_${task.id}" value="${task.template_id}">
      <input type="hidden" name="task_description" id="task_description_hidden_${task.id}" value="${task.task}">
      <input type="hidden" name="start_date" id="start_date_hidden_${task.id}" value="${task.start_date}">
      <input type="hidden" name="end_date" id="end_date_hidden_${task.id}" value="${task.end_date}">
      <input type="hidden" name="priority" id="priority_hidden_${task.id}" value="${task.priority}">

      <!-- Action buttons -->
      <button type="button" onclick="enableRowEdit(${task.id})" id="editBtn-${task.id}">Edit</button>
      <button type="button" id="saveBtn-${task.id}" style="display:none;"
              onclick="updateHiddenFieldsAndConfirm(event, ${task.id})">Save</button>
      <button type="button" id="cancelBtn-${task.id}" style="display:none;"
              onclick="cancelEdit(${task.id})">Cancel</button>
      <button type="button" onclick="showDeleteModal(${task.id})" id="deleteBtn-${task.id}">Delete</button>
    </td>
  `;

  tableBody.insertBefore(tr, addTaskRow);

  document.querySelector(".add-task-btn").style.display = "inline-block";
}

function enableRowEdit(taskId) {
  const row = document.querySelector(`#task-row-${taskId}`);
  const editableFields = row.querySelectorAll(".editable");
  originalValues[taskId] = {};

  editableFields.forEach((cell) => {
    const field = cell.dataset.field;
    const value = cell.textContent.trim();
    originalValues[taskId][field] = value;

    let input;
    let inputName;
    let inputType = "text";

    if (field.includes("date")) {
      inputType = "date";
      inputName = field.replace("_display", "");
    } else if (field === "priority_display") {
      inputType = "select";
      inputName = "priority";
    } else if (field === "task_display") {
      inputType = "select-task";
      inputName = "template_id";
    }

    if (inputType === "select-task") {
      input = document.createElement("select");
      input.name = inputName;
      input.className = "task-select";

      let currentTemplateId = cell.dataset.templateId;

      taskTemplates.forEach((template) => {
        const option = document.createElement("option");
        option.value = template.id;
        option.textContent = template.description;
        if (template.id == currentTemplateId) {
          option.selected = true;
        }
        input.appendChild(option);
      });

      input.addEventListener("change", function () {
        const selectedOptionText = this.options[this.selectedIndex].text;
        const taskDescriptionHidden = document.getElementById(
          `task_description_hidden_${taskId}`
        );
        if (taskDescriptionHidden) {
          taskDescriptionHidden.value = selectedOptionText;
        }
      });
    } else if (inputType === "select") {
      input = document.createElement("select");
      input.name = inputName;
      ["Low", "Medium", "High"].forEach((level) => {
        const option = document.createElement("option");
        option.value = level;
        option.textContent = level;
        if (level === value) option.selected = true;
        input.appendChild(option);
      });
    } else {
      input = document.createElement("input");
      input.type = inputType;
      input.name = inputName;
      input.value = value;
    }

    cell.innerHTML = "";
    cell.appendChild(input);
  });

  document.getElementById(`editBtn-${taskId}`).style.display = "none";
  document.getElementById(`deleteBtn-${taskId}`).style.display = "none";

  document.getElementById(`saveBtn-${taskId}`).style.display = "inline-block";
  document.getElementById(`cancelBtn-${taskId}`).style.display = "inline-block";
}

function cancelEdit(taskId) {
  const row = document.querySelector(`#task-row-${taskId}`);
  const editableFields = row.querySelectorAll(".editable");

  editableFields.forEach((cell) => {
    const field = cell.dataset.field;
    cell.textContent = originalValues[taskId][field]; // Restore original display value
  });

  document.getElementById(`saveBtn-${taskId}`).style.display = "none";
  document.getElementById(`cancelBtn-${taskId}`).style.display = "none";

  document.getElementById(`editBtn-${taskId}`).style.display = "inline-block";
  document.getElementById(`deleteBtn-${taskId}`).style.display = "inline-block";
}

async function updateHiddenFieldsAndConfirm(event, taskId) {
  event.preventDefault();

  const row = document.querySelector(`#task-row-${taskId}`);
  const startInput = row.querySelector(
    'td[data-field="start_date_display"] input'
  );
  const endInput = row.querySelector('td[data-field="end_date_display"] input');
  const prioSelect = row.querySelector(
    'td[data-field="priority_display"] select'
  );
  const taskSelect = row.querySelector('td[data-field="task_display"] select');

  const payload = new URLSearchParams();
  payload.append("action", "edit_task");
  payload.append("task_id", taskId);
  payload.append("template_id", taskSelect.value);
  payload.append("start_date", startInput.value);
  payload.append("end_date", endInput.value);
  payload.append("priority", prioSelect.value);

  try {
    const resp = await fetch(window.location.href, {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: payload.toString(),
    });

    const result = await resp.json();
    if (!result.success) {
      alert("Error: " + result.message);
      return;
    }

    row.querySelector('td[data-field="task_display"]').textContent =
      result.data.task;
    row.querySelector('td[data-field="start_date_display"]').textContent =
      result.data.start_date;
    row.querySelector('td[data-field="end_date_display"]').textContent =
      result.data.end_date;
    row.querySelector('td[data-field="priority_display"]').textContent =
      result.data.priority;

    document.getElementById(`saveBtn-${taskId}`).style.display = "none";
    document.getElementById(`cancelBtn-${taskId}`).style.display = "none";

    document.getElementById(`editBtn-${taskId}`).style.display = "inline-block";
    document.getElementById(`deleteBtn-${taskId}`).style.display =
      "inline-block";

    showTemporaryMessage("Task updated successfully.", "success");
  } catch (err) {
    console.error(err);
    alert("Unexpected error; check console.");
  }
}

function showTemporaryMessage(msg, type = "success") {
  const div = document.createElement("div");
  div.textContent = msg;
  div.className = type === "success" ? "success-message" : "error-message";
  document.querySelector(".mgr-main-content").prepend(div);
  setTimeout(() => div.remove(), 3000);
}

let pendingDeleteTaskId = null;

function showDeleteModal(taskId) {
  pendingDeleteTaskId = taskId;
  document.getElementById("deleteModal").style.display = "flex";
}

document
  .getElementById("deleteConfirmBtn")
  .addEventListener("click", async () => {
    if (!pendingDeleteTaskId) return;

    try {
      const payload = new URLSearchParams({
        action: "delete_task",
        task_id: pendingDeleteTaskId,
      });

      const resp = await fetch(window.location.href, {
        method: "POST",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: payload.toString(),
      });
      const result = await resp.json();

      if (!result.success) {
        alert("Error deleting task: " + (result.message || "unknown"));
      } else {
        // remove the row from the DOM
        const row = document.getElementById(`task-row-${pendingDeleteTaskId}`);
        row && row.remove();
        showTemporaryMessage("Task deleted.", "success");
      }
    } catch (err) {
      console.error(err);
      alert("Network or server error.");
    } finally {
      pendingDeleteTaskId = null;
      closeModal("deleteModal");
    }
  });

function searchTasks() {
  const input = document.getElementById("searchBar").value.toLowerCase();
  const rows = document.querySelectorAll("#taskBody tr:not(#add-task-row)");

  rows.forEach((row) => {
    const text = row.innerText.toLowerCase();
    row.style.display = text.includes(input) ? "" : "none";
  });
}

function showModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.style.display = "flex";
}
function closeModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.style.display = "none";
}
