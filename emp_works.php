<?php

include "database/queries.php";

session_start();

$userFullname = $_SESSION['full_name'] ?? '';
$userId = $_SESSION["uid"] ?? 0;

$tasks = getEmployeeTasks($_SESSION["user_code"]);
$today = date('Y-m-d');

$statusMap = [
  'working' => 'In Progress',
  'done' => 'Done',
  'stuck' => 'Not Started'
];

$statusCounts = [
  'Not Started' => 0,
  'In Progress' => 0,
  'Done' => 0,
  'Overdue' => 0
];

foreach ($tasks as &$task) {
  if (isset($statusMap[$task['status']])) {
    $task['status'] = $statusMap[$task['status']];
  }

  if ($task['status'] !== 'Done' && $task['end_date'] < $today) {
    $task['status'] = 'Overdue';
  }
}
unset($task);


foreach ($tasks as $task) {
  if (isset($statusCounts[$task['status']])) {
    $statusCounts[$task['status']]++;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_task_status') {
  header('Content-Type: application/json');

  // Pull and validate inputs
  $taskId = $_POST['task_id'] ?? null;
  $status = $_POST['status'] ?? null;

  $validStatuses = ['Not Started', 'In Progress', 'Done', 'Overdue'];

  if (!$taskId || !in_array($status, $validStatuses)) {
    echo json_encode([
      'success' => false,
      'message' => 'Invalid task ID or status.'
    ]);
    exit;
  }

  $result = updateTaskStatus($taskId, $status);

  if ($result === true) {
    echo json_encode([
      'success' => true,
      'data' => [
        'status' => $status
      ]
    ]);
  } else {
    echo json_encode([
      'success' => false,
      'message' => $result
    ]);
  }

  exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/emp-db.css" type="text/css" />
  <script src="./assets/js/main/clock.js" defer></script>
  <title>My Works</title>
</head>

<body>
  <div class="layout">
    <?php include './php/includes/emp_sidebar.php'; ?>

    <main class="emp-main-content">
      <header class="emp-header">
        <div>
          <h1>Welcome, <?= $userFullname ?></h1>
          <p class="emp-id"><b>Employee</b> || ID No.: <?= str_pad($userId, 4, '0', STR_PAD_LEFT) ?>
          </p>
        </div>
        <div class="emp-branch-info">
          <span>Branch: <strong>WVSU-BINHI TBI</strong> | Time: </span>
          <span id="clock">--:--:--</span>
        </div>
      </header>

      <div class="emp-search-filter">
        <input type="text" id="searchBar" placeholder="Search a task" onkeyup="searchTasks()" />
      </div>

      <div class="emp-title">
        <h1><br>My Works</h1><br>
        <p>The "My Works" page serves as a personalized overview where employees can track all the tasks they have
          accomplished for the day.</p><br><br><br>
      </div>

      <div class="emp-filters">
        <button type="button">Not Started <span
            id="count-not-started"><?php echo $statusCounts['Not Started']; ?></span></button>
        <button type="button">In Progress <span
            id="count-in-progress"><?php echo $statusCounts['In Progress']; ?></span></button>
        <button type="button">Done <span id="count-done"><?php echo $statusCounts['Done']; ?></span></button>
        <button type="button">Overdue <span id="count-overdue"><?php echo $statusCounts['Overdue']; ?></span></button>
      </div>

      <div class="emp-title">
        <h1>Employee Task Overview</h1><br>
      </div>
      <section class="emp-task-table">
        <table>
          <thead>
            <tr>
              <th>TASK ID</th>
              <th>TASK NAME</th>
              <th>START DATE</th>
              <th>END DATE</th>
              <th>TIMELINE</th>
              <th>STATUS</th>
              <th>PRIORITY</th>
              <th>ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($tasks as $task): ?>
              <tr id="task-row-<?= $task['id'] ?>">
                <td><?= $task['id'] ?></td>
                <td><?= $task['task'] ?></td>
                <td><?= $task['start_date'] ?></td>
                <td><?= $task['end_date'] ?></td>
                <td><?= $task['timeline'] ?></td>
                <td class="editable" data-field="status_display"><?= $task['status'] ?></td>
                <td><?= $task['priority'] ?></td>
                <td>
                  <button type="button" onclick="enableRowEdit(<?= $task['id'] ?>)"
                    id="editBtn-<?= $task['id'] ?>">Edit</button>
                  <button type="submit" style="display:none;" id="saveBtn-<?= $task['id'] ?>"
                    onclick="updateHiddenFieldsAndConfirm(event, <?= $task['id'] ?>)">Save</button>
                  <button type="button" id="cancelBtn-<?= $task['id'] ?>" style="display:none;"
                    onclick="cancelEdit(<?= $task['id'] ?>)">
                    Cancel
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </section>
    </main>
  </div>
  </main>
  </div>
  <div class="fill-in-footer">
    <h3>All Rights Reserved by Paul Kaldi || Foreal Solutions || Hexed Devs @ 2025</h3>
  </div>
  <script>
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

        if (field === 'status_display') {
          inputType = 'select-status';
          inputName = 'status';
        }

        if (inputType === 'select-status') {
          input = document.createElement('select');
          input.name = inputName;
          ['Not Started', 'In Progress', 'Done', 'Overdue'].forEach(level => {
            const option = document.createElement('option');
            option.value = level;
            option.textContent = level;
            if (level === value) option.selected = true;
            input.appendChild(option);
          });
        }

        cell.innerHTML = '';
        cell.appendChild(input);
      });

      document.getElementById(`editBtn-${taskId}`).style.display = 'none';

      document.getElementById(`saveBtn-${taskId}`).style.display = 'inline-block';
      document.getElementById(`cancelBtn-${taskId}`).style.display = 'inline-block';
    }

    function cancelEdit(taskId) {
      const row = document.querySelector(`#task-row-${taskId}`);
      const editableFields = row.querySelectorAll('.editable');

      editableFields.forEach(cell => {
        const field = cell.dataset.field;
        cell.textContent = originalValues[taskId][field];
      });

      document.getElementById(`saveBtn-${taskId}`).style.display = 'none';
      document.getElementById(`cancelBtn-${taskId}`).style.display = 'none';

      // re-show normal buttons
      document.getElementById(`editBtn-${taskId}`).style.display = 'inline-block';
    }

    async function updateHiddenFieldsAndConfirm(event, taskId) {
      event.preventDefault();

      const row = document.querySelector(`#task-row-${taskId}`);
      const statusSelect = row.querySelector('td[data-field="status_display"] select');

      const payload = new URLSearchParams();
      payload.append('action', 'update_task_status');
      payload.append('task_id', taskId);
      payload.append('status', statusSelect.value);

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

        row.querySelector('td[data-field="status_display"]').textContent = result.data.status;

        document.getElementById(`saveBtn-${taskId}`).style.display = 'none';
        document.getElementById(`cancelBtn-${taskId}`).style.display = 'none';
        document.getElementById(`editBtn-${taskId}`).style.display = 'inline-block';

        showTemporaryMessage('Task status updated successfully.', 'success');

      } catch (err) {
        console.error(err);
        alert('Unexpected error occurred.');
      }
    }

    function showTemporaryMessage(message, type = 'info') {
      const msg = document.createElement('div');
      msg.textContent = message;
      msg.className = `temp-msg ${type}`;
      document.body.appendChild(msg);
      setTimeout(() => msg.remove(), 3000);
    }

  </script>
</body>

</html>