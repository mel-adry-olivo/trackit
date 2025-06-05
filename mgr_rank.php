<?php
session_start();
include "database/queries.php";

if ($_SESSION['role'] !== 'manager') {
  header('HTTP/1.1 403 Forbidden');
  exit('Access denied.');
}

$monthStart = date('Y-m-01');
$monthEnd = date('Y-m-t');

$employees = getAllEmployees();

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
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Employee Evaluation</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
  <script src="./assets/js/main/clock.js" defer></script>
</head>

<body>

  <div class="top-banner" style="
  background: url('./assets/img/kapekapeah2.jpg') no-repeat center center / cover;
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
    <main class="mgr-main-content">
      <div class="mgr-header">
        <div class="mgr-title">
          <h1>Evaluation Rankings</h1>
          <p>See employee performance for the past month</p>
        </div>
        <div class="mgr-branch-info">
          <span>Branch: <strong>WVSU-BINHI TBI</strong> | Time: </span>
          <span id="clock">--:--:--</span>
        </div>
      </div>
      <div class="mgr_rank-overview">
        <div href="mgr_rank.php" class="link-block">
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
        </div>
      </div>
  </div>
  </main>
  </div>
</body>

</html>