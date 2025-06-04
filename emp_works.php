<?php

include "database/queries.php";

session_start();

$userFullname = $_SESSION['full_name'] ?? '';
$userId = $_SESSION["uid"] ?? 0;

$tasks = getEmployeeTasks($_SESSION["uid"]);
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
  if ($task['status'] !== 'done' && $task['end_date'] < $today) {
    $task['status'] = 'Overdue';
  } elseif (isset($statusMap[$task['status']])) {
    $task['status'] = $statusMap[$task['status']];
  }
}
unset($task);

foreach ($tasks as $task) {
  if (isset($statusCounts[$task['status']])) {
    $statusCounts[$task['status']]++;
  }
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
              <tr>
                <td><?= $task['id'] ?></td>
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
  </main>
  </div>
  <div class="fill-in-footer">
    <h3>All Rights Reserved by Paul Kaldi || Foreal Solutions || Hexed Devs @ 2025</h3>
  </div>

</body>

</html>