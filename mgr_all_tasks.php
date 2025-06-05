<?php

include "database/queries.php";

session_start();

$userFullname = $_SESSION['full_name'] ?? '';
$userId = $_SESSION["uid"] ?? 0;

$allTasks = getManagerAllTasks($userId);

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
    <!-- Sidebar -->
    <?php include "./php/includes/mgr_sidebar.php"; ?>


    <!-- Main Content -->
    <main class="mgr-content">
      <header class="mgr-header">
        <div>
          <h1>Welcome, <?= $userFullname ?></h1>
          <p class="mgr-id"><b>Employee</b> || ID No.: <?= str_pad($userId, 4, '0', STR_PAD_LEFT) ?>
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
</body>

</html>