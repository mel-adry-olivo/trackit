<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();

session_start();

include __DIR__ . '/database/queries.php';

$userFullname = $_SESSION['full_name'] ?? '';
$userCode = $_SESSION["user_code"] ?? null;

$tasks = getManagerAllTasks($userCode);
$today = date('Y-m-d');

$taskTemplates = getAllTaskTemplates() ?? [];
$employees = getAllEmployees();

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
    // if createTask() returns an error string
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
  <title>Assign Task</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <script src="./assets/js/main/clock.js" defer></script>
  <script src="./assets/js/main/search.js" defer></script>
  <script defer>
    function startOverlayAnimation() {
      const overlay = document.querySelector('.mgr-home-overlay');
      const button = document.querySelector('.mgr-home-btn');

      if (overlay.classList.contains('slide-up')) {
        overlay.classList.remove('slide-up');
        button.textContent = "Get Started";
        document.body.style.overflow = 'hidden';
        window.scrollTo({ top: 0, behavior: 'smooth' });
      } else {
        overlay.classList.add('slide-up');
        button.textContent = "Hey there!";
        document.body.style.overflow = 'auto';
      }
    }
  </script>
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
    <?php include __DIR__ . '/php/includes/mgr_sidebar.php'; ?>


    <!-- Main Content -->
    <main class="mgr-main-content">
      <?php if (!empty($errorMessage)): ?>
        <div class="error-message"
          style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
          <?= htmlspecialchars($errorMessage) ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($successMessage)): ?>
        <div class="success-message"
          style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
          <?= htmlspecialchars($successMessage) ?>
        </div>
      <?php endif; ?>

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
          <h1><br>Assign a Task</h1><br>
          <p>Click the "Add Task" button and start assigning tasks to your employees with ease and build virtual
            connection with them!</p><br><br><br>
        </div>

        <div class="emp-filters">
          <button type="button">Not Started <span
              id="count-not-started"><?php echo $statusCounts['Not Started']; ?></span></button>
          <button type="button">In Progress <span
              id="count-in-progress"><?php echo $statusCounts['In Progress']; ?></span></button>
          <button type="button">Done <span id="count-done"><?php echo $statusCounts['Done']; ?></span></button>
          <button type="button">Overdue <span id="count-overdue"><?php echo $statusCounts['Overdue']; ?></span></button>
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
              <?php foreach ($tasks as $task): ?>
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


  <script>
    const employees = <?php echo json_encode($employees) ?>;
    const taskTemplates = <?php echo json_encode($taskTemplates) ?>;
  </script>
  <script src="./assets/js/main/assignTask.js"></script>

</body>

</html>