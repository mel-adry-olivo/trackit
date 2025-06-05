<?php


include "database/queries.php";

session_start();

$userFullname = $_SESSION['full_name'] ?? '';
$userCode = $_SESSION["user_code"] ?? null;

$allTasks = getManagerAllTasks($userCode);
$mgrTasks = getTasksOfManager($userCode);
$today = date("Y-m-d");

$taskTemplates = getAllTaskTemplates();
$employees = getAllEmployees();

foreach ($allTasks as &$task) {
  if ($task['status'] !== 'done' && $task['end_date'] < $today) {
    $task['status'] = 'Overdue';
  } elseif (isset($statusMap[$task['status']])) {
    $task['status'] = $statusMap[$task['status']];
  }
}
unset($task);

$errorMessage = '';
$successMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit_task') {
  header('Content-Type: application/json');

  // pull & validate inputs
  $taskId = $_POST['task_id'] ?? null;
  $templateId = $_POST['template_id'] ?? null;
  $startDate = $_POST['start_date'] ?? null;
  $endDate = $_POST['end_date'] ?? null;
  $priority = $_POST['priority'] ?? null;

  $result = updateTask($taskId, $templateId, $startDate, $endDate, $priority);

  if ($result === true) {
    echo json_encode([
      'success' => true,
      'data' => [
        'task' => getTemplateDescription($templateId),
        'start_date' => $startDate,
        'end_date' => $endDate,
        'priority' => $priority
      ]
    ]);
  } else {
    // if updateTask() returns an error string
    echo json_encode([
      'success' => false,
      'message' => $result
    ]);
  }
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_task') {
  header('Content-Type: application/json');
  $taskId = $_POST['task_id'] ?? null;
  $result = deleteTask($taskId);

  if ($result === true) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'message' => $result]);
  }
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_task') {
  header('Content-Type: application/json');

  // Pull & validate inputs for a new task
  $assigneeCode = $_POST['assignee_code'] ?? null;
  $templateId = $_POST['template_id'] ?? null;
  $startDate = $_POST['start_date'] ?? null;
  $endDate = $_POST['end_date'] ?? null;
  $priority = $_POST['priority'] ?? null;
  $managerCode = $userCode;

  $result = createTask($managerCode, $assigneeCode, $templateId, $startDate, $endDate, $priority);

  if (is_int($result)) {
    $newTask = getTaskById($result);
    echo json_encode([
      'success' => true,
      'data' => $newTask
    ]);
  } else {
    echo json_encode([
      'success' => false,
      'message' => is_string($result) ? $result : 'Failed to create task.'
    ]);
  }
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>All Tasks</title>
  <script src="./assets/js/main/clock.js" defer></script>
  <script src="./assets/js/main/search.js" defer></script>
  <link rel="stylesheet" href="assets/css/styles.css" />
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


      <div class="mgr-search-filter">
        <input type="text" id="searchBar" placeholder="Search a task" onkeyup="searchTasks()" />

        <div class="mgr-title">
          <h1><br>All Employee Tasks</h1><br>
          <p>Refer here to view real-time tasks.</p><br><br><br>
        </div>
        <div class="mgr-title">
          <h1>Manager Task Overview</h1><br>
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
              <?php foreach ($mgrTasks as $task): ?>
                <form method="POST">
                  <input type="hidden" name="action" value="edit_task">
                  <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                  <input type="hidden" name="template_id" id="template_id_hidden_<?= $task['id'] ?>"
                    value="<?= $task['template_id'] ?>">
                  <input type="hidden" name="task_description" id="task_description_hidden_<?= $task['id'] ?>"
                    value="<?= htmlspecialchars($task['task']) ?>">

                  <input type="hidden" name="start_date" id="start_date_hidden_<?= $task['id'] ?>"
                    value="<?= $task['start_date'] ?>">
                  <input type="hidden" name="end_date" id="end_date_hidden_<?= $task['id'] ?>"
                    value="<?= $task['end_date'] ?>">
                  <input type="hidden" name="priority" id="priority_hidden_<?= $task['id'] ?>"
                    value="<?= $task['priority'] ?>">

                  <tr id="task-row-<?= $task['id'] ?>">
                    <td><?= $task['assignee_name'] ?></td>
                    <td><?= $task['assignee_code'] ?></td>
                    <td class="editable" data-field="task_display" data-template-id="<?= $task['template_id'] ?>">
                      <?= htmlspecialchars($task['task']) ?>
                    </td>
                    <td class="editable" data-field="start_date_display"><?= $task['start_date'] ?></td>
                    <td class="editable" data-field="end_date_display"><?= $task['end_date'] ?></td>
                    <td><?= $task['timeline'] ?></td>
                    <td><?= $task['status'] ?></td>
                    <td class="editable" data-field="priority_display"><?= $task['priority'] ?></td>
                    <td>
                      <button type="button" onclick="enableRowEdit(<?= $task['id'] ?>)"
                        id="editBtn-<?= $task['id'] ?>">Edit</button>
                      <button type="submit" style="display:none;" id="saveBtn-<?= $task['id'] ?>"
                        onclick="updateHiddenFieldsAndConfirm(event, <?= $task['id'] ?>)">Save</button>
                      <button type="button" id="cancelBtn-<?= $task['id'] ?>" style="display:none;"
                        onclick="cancelEdit(<?= $task['id'] ?>)">
                        Cancel
                      </button>
                      <button type="button" onclick="showDeleteModal(<?= $task['id'] ?>)"
                        id="deleteBtn-<?= $task['id'] ?>">Delete</button>
                    </td>
                  </tr>
                </form>
              <?php endforeach; ?>
            </tbody>
          </table>

        </section>

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
                <form method="POST">
                  <input type="hidden" name="action" value="edit_task">
                  <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                  <input type="hidden" name="template_id" id="template_id_hidden_<?= $task['id'] ?>"
                    value="<?= $task['template_id'] ?>">
                  <input type="hidden" name="task_description" id="task_description_hidden_<?= $task['id'] ?>"
                    value="<?= htmlspecialchars($task['task']) ?>">

                  <input type="hidden" name="start_date" id="start_date_hidden_<?= $task['id'] ?>"
                    value="<?= $task['start_date'] ?>">
                  <input type="hidden" name="end_date" id="end_date_hidden_<?= $task['id'] ?>"
                    value="<?= $task['end_date'] ?>">
                  <input type="hidden" name="priority" id="priority_hidden_<?= $task['id'] ?>"
                    value="<?= $task['priority'] ?>">

                  <tr id="task-row-<?= $task['id'] ?>">
                    <td><?= $task['assignee_name'] ?></td>
                    <td><?= $task['assignee_code'] ?></td>
                    <td class="editable" data-field="task_display" data-template-id="<?= $task['template_id'] ?>">
                      <?= htmlspecialchars($task['task']) ?>
                    </td>
                    <td class="editable" data-field="start_date_display"><?= $task['start_date'] ?></td>
                    <td class="editable" data-field="end_date_display"><?= $task['end_date'] ?></td>
                    <td><?= $task['timeline'] ?></td>
                    <td><?= $task['status'] ?></td>
                    <td class="editable" data-field="priority_display"><?= $task['priority'] ?></td>
                    <td>
                      <button type="button" onclick="enableRowEdit(<?= $task['id'] ?>)"
                        id="editBtn-<?= $task['id'] ?>">Edit</button>
                      <button type="submit" style="display:none;" id="saveBtn-<?= $task['id'] ?>"
                        onclick="updateHiddenFieldsAndConfirm(event, <?= $task['id'] ?>)">Save</button>
                      <button type="button" id="cancelBtn-<?= $task['id'] ?>" style="display:none;"
                        onclick="cancelEdit(<?= $task['id'] ?>)">
                        Cancel
                      </button>
                      <button type="button" onclick="showDeleteModal(<?= $task['id'] ?>)"
                        id="deleteBtn-<?= $task['id'] ?>">Delete</button>
                    </td>
                  </tr>
                </form>
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
<script>
  const employees = <?= json_encode($employees); ?>;
  const taskTemplates = <?= json_encode($taskTemplates); ?>;
  let currentFormToSubmit = null;

  const originalValues = {};

  function enableRowEdit(taskId) {
    const row = document.querySelector(`#task-row-${taskId}`);
    const editableFields = row.querySelectorAll('.editable');
    originalValues[taskId] = {};

    editableFields.forEach(cell => {
      const field = cell.dataset.field;
      const value = cell.textContent.trim();
      originalValues[taskId][field] = value;

      let input;
      let inputName;
      let inputType = 'text';

      if (field.includes('date')) {
        inputType = 'date';
        inputName = field.replace('_display', '');
      } else if (field === 'priority_display') {
        inputType = 'select';
        inputName = 'priority';
      } else if (field === 'task_display') {
        inputType = 'select-task';
        inputName = 'template_id';
      }

      if (inputType === 'select-task') {
        input = document.createElement('select');
        input.name = inputName;
        input.className = 'task-select';

        let currentTemplateId = cell.dataset.templateId;

        taskTemplates.forEach(template => {
          const option = document.createElement('option');
          option.value = template.id;
          option.textContent = template.description;
          if (template.id == currentTemplateId) {
            option.selected = true;
          }
          input.appendChild(option);
        });

        input.addEventListener('change', function () {
          const selectedOptionText = this.options[this.selectedIndex].text;
          const taskDescriptionHidden = document.getElementById(`task_description_hidden_${taskId}`);
          if (taskDescriptionHidden) {
            taskDescriptionHidden.value = selectedOptionText;
          }
        });

      } else if (inputType === 'select') {
        input = document.createElement('select');
        input.name = inputName;
        ['Low', 'Medium', 'High'].forEach(level => {
          const option = document.createElement('option');
          option.value = level;
          option.textContent = level;
          if (level === value) option.selected = true;
          input.appendChild(option);
        });
      } else { // For regular text/date inputs
        input = document.createElement('input');
        input.type = inputType;
        input.name = inputName;
        input.value = value;
      }

      cell.innerHTML = '';
      cell.appendChild(input);
    });

    document.getElementById(`editBtn-${taskId}`).style.display = 'none';
    document.getElementById(`deleteBtn-${taskId}`).style.display = 'none';

    document.getElementById(`saveBtn-${taskId}`).style.display = 'inline-block';
    document.getElementById(`cancelBtn-${taskId}`).style.display = 'inline-block';
  }

  function cancelEdit(taskId) {
    const row = document.querySelector(`#task-row-${taskId}`);
    const editableFields = row.querySelectorAll('.editable');

    editableFields.forEach(cell => {
      const field = cell.dataset.field;
      cell.textContent = originalValues[taskId][field]; // Restore original display value
    });

    document.getElementById(`saveBtn-${taskId}`).style.display = 'none';
    document.getElementById(`cancelBtn-${taskId}`).style.display = 'none';

    // re-show normal buttons
    document.getElementById(`editBtn-${taskId}`).style.display = 'inline-block';
    document.getElementById(`deleteBtn-${taskId}`).style.display = 'inline-block';
  }

  async function updateHiddenFieldsAndConfirm(event, taskId) {
    event.preventDefault();

    const row = document.querySelector(`#task-row-${taskId}`);
    const startInput = row.querySelector('td[data-field="start_date_display"] input');
    const endInput = row.querySelector('td[data-field="end_date_display"] input');
    const prioSelect = row.querySelector('td[data-field="priority_display"] select');
    const taskSelect = row.querySelector('td[data-field="task_display"] select');

    // Gather form-data
    const payload = new URLSearchParams();
    payload.append('action', 'edit_task');
    payload.append('task_id', taskId);
    payload.append('template_id', taskSelect.value);
    payload.append('start_date', startInput.value);
    payload.append('end_date', endInput.value);
    payload.append('priority', prioSelect.value);

    try {
      const resp = await fetch(window.location.href, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: payload.toString(),
      });

      const result = await resp.json();
      if (!result.success) {
        alert('Error: ' + result.message);
        return;
      }

      row.querySelector('td[data-field="task_display"]').textContent = result.data.task;
      row.querySelector('td[data-field="start_date_display"]').textContent = result.data.start_date;
      row.querySelector('td[data-field="end_date_display"]').textContent = result.data.end_date;
      row.querySelector('td[data-field="priority_display"]').textContent = result.data.priority;

      // hide the inline editors
      document.getElementById(`saveBtn-${taskId}`).style.display = 'none';
      document.getElementById(`cancelBtn-${taskId}`).style.display = 'none';

      // re-show the normal actions
      document.getElementById(`editBtn-${taskId}`).style.display = 'inline-block';
      document.getElementById(`deleteBtn-${taskId}`).style.display = 'inline-block';


    } catch (err) {
      console.error(err);
      alert('Unexpected error; check console.');
    }
  }

  function searchTasks() {
    const input = document.getElementById("searchBar").value.toLowerCase();
    const rows = document.querySelectorAll("#taskBody tr:not(#add-task-row)");

    rows.forEach(row => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(input) ? "" : "none";
    });
  }

  let pendingDeleteTaskId = null;

  function showDeleteModal(taskId) {
    pendingDeleteTaskId = taskId;
    document.getElementById('deleteModal').style.display = 'flex';
  }

  document.getElementById('deleteConfirmBtn').addEventListener('click', async () => {
    if (!pendingDeleteTaskId) return;

    try {
      const payload = new URLSearchParams({
        action: 'delete_task',
        task_id: pendingDeleteTaskId
      });

      const resp = await fetch(window.location.href, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: payload.toString()
      });
      const result = await resp.json();

      if (!result.success) {
        alert('Error deleting task: ' + (result.message || 'unknown'));
      } else {
        // remove the row from the DOM
        const row = document.getElementById(`task-row-${pendingDeleteTaskId}`);
        row && row.remove();
      }
    } catch (err) {
      console.error(err);
      alert('Network or server error.');
    } finally {
      pendingDeleteTaskId = null;
      closeModal('deleteModal');
    }
  });




  function showModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = 'flex';
  }
  function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = 'none';
  }


</script>

</body>

</html>