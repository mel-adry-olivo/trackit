<?php

include "../database/queries.php";

session_start();

$userFullname = $_SESSION['full_name'] ?? '';
$userCode = $_SESSION["user_code"] ?? null;

$allUsers = getAllUsers();

$tasks = getManagerAllTasks($userCode, 5);
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


$monthlyScores = getMonthlyEvaluationScores($monthStart, $monthEnd);
$isEndOfMonth = date('j') == date('t');

usort($monthlyScores, function ($a, $b) {
  return $b['total_score'] <=> $a['total_score'];
});

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/styles.css" />
  <script src="../assets/js/main/clock.js" defer></script>
  <script>
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
        document.getElementById('main-dashboard').scrollIntoView({ behavior: 'smooth' });
      }
    }
  </script>
</head>

<body class="manager">
  <div class="mgr-home-overlay" style="background: url('./assets/img/admin-db.jpg') no-repeat center center / cover;">
    <div class="mgr-home-content">
      <div class="mgr-home-tag">Paul Kaldi Admin Portal</div>
      <h2 class="mgr-home-title">Oversee Operations with Precision.</h2>
      <p class="mgr-home-description">
        From user management to system analytics, take control of your platform’s backend with clarity, security, and
        efficiency.
      </p>
      <button class="mgr-home-btn" onclick="startOverlayAnimation()">Get Started</button>
    </div>

    <div class="mgr-home-mission-box">
      <h4 class="mgr-home-mission-title">Our Mission</h4>
      <p class="mgr-home-mission-text">
        Empower administrators with powerful tools to manage users, ensure data integrity, and maintain seamless
        operational workflows across the system.
      </p>
    </div>
  </div>

  <div class="mgr-container" id="main-dashboard">

    <?php include "../php/includes/adm_sidebar.php"; ?>

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

              // Get the day of the week (0 = Sunday, 6 = Saturday)
              $dayOfWeek = (int) $today->format('w');

              // Clone today's date and move to the start of the week (Sunday)
              $startOfWeek = clone $today;
              $startOfWeek->modify("-$dayOfWeek days");

              // Print 7 days (Sunday to Saturday)
              for ($i = 0; $i < 7; $i++) {
                $date = clone $startOfWeek;
                $date->modify("+$i days");
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
        <!-- <input type="text" id="searchBar" placeholder="Search a task" onkeyup="searchTasks()" /> -->

        <div class="row">
          <div class="emp-filters">
            <button type="button">Not Started <span
                id="count-not-started"><?php echo $statusCounts['Not Started']; ?></span></button>
            <button type="button">In Progress <span
                id="count-in-progress"><?php echo $statusCounts['In Progress']; ?></span></button>
            <button type="button">Done <span id="count-done"><?php echo $statusCounts['Done']; ?></span></button>
            <button type="button">Overdue <span
                id="count-overdue"><?php echo $statusCounts['Overdue']; ?></span></button>
          </div>
          <div class="mgr_rank-overview">
            <a href="mgr_rank.php" class="link-block">
              <div class="leaderboard-container">
                <div class="leaderboard-header">
                  <h2>Current Leaderboard</h2>
                  <p>Monthly Performance Rankings</p>
                </div>
                <?php if ($isEndOfMonth && !empty($monthlyScores)): ?>
                  <div class="compact-leaderboard">
                    <?php foreach ($monthlyScores as $index => $emp): ?>
                      <div class="leaderboard-item rank-<?= $index + 1 ?>">
                        <span class="rank-number"><?= $index + 1 ?></span>
                        <span class="employee-name"><?= htmlspecialchars($emp['full_name']) ?></span>
                        <span class="rank-score"><?= intval($emp['total_score']) ?> pts</span>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php else: ?>
                  <div class="leaderboard-placeholder">
                    <p style="text-align: center; font-size: 1.1rem; margin-top: 1rem;">
                      Rankings will be available at the end of the month.
                    </p>
                  </div>
                <?php endif; ?>
              </div>
            </a>
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
                <th>TASK</th>
                <th>START DATE</th>
                <th>END DATE</th>
                <th>TIMELINE</th>
                <th>STATUS</th>
                <th>PRIORITY</th>
              </tr>
            </thead>
            <tbody id="taskBody">
              <?php foreach ($tasks as $task): ?>
                <tr>
                  <td><?= $task['assignee_name'] ?></td>
                  <td><?= $task['assignee_id'] ?></td>
                  <td><?= $task['task'] ?></td>
                  <td><?= $task['start_date'] ?></td>
                  <td><?= $task['end_date'] ?></td>
                  <td><?= $task['timeline'] ?></td>
                  <td><?= $task['status'] ?></td>
                  <td><?= $task['priority'] ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
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
              <?php foreach ($allUsers as $user): ?>
                <tr>
                  <td><?= htmlspecialchars($user['full_name']) ?></td>
                  <td><?= htmlspecialchars($user['job_title'] ?? 'N/A') ?></td>
                  <td>WVSU Binhi-TBI</td>
                  <td>Coffee Roastery & Shop</td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

  </div>

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
</body>

</html>