<?php
session_start();
include "database/queries.php";
if ($_SESSION['role'] !== 'manager') {
  header('HTTP/1.1 403 Forbidden');
  exit('Access denied.');
}
$employees = getAllEmployees();
$questions = [
  "Q1. Punctuality: Arrives on time and is ready to work at scheduled shifts.",
  "Q2. Customer Interaction: Greets and engages customers in a friendly and professional manner.",
  "Q3. Team Collaboration: Cooperates well with team members and supports others during busy times.",
  "Q4. Work Ethic: Stays focused and productive throughout the shift.",
  "Q5. Cleanliness & Hygiene: Maintains personal hygiene and keeps work areas clean.",
  "Q6. Problem Solving: Handles customer concerns or issues calmly and effectively.",
  "Q7. Product Knowledge: Demonstrates understanding of menu items and ingredients.",
  "Q8. Initiative: Takes proactive steps without needing constant supervision.",
  "Q9. Adaptability: Adjusts well to changes in tasks, roles, or customer flow.",
  "Q10. Professionalism: Demonstrates respect, responsibility, and appropriate conduct at work."
];

// Get current week's start and end dates (Monday–Sunday)
$startOfWeek = date('F j, Y', strtotime('monday this week'));
$endOfWeek = date('F j, Y', strtotime('sunday this week'));
$weekRange = "$startOfWeek – $endOfWeek";


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Employee Evaluation</title>
  <link rel="stylesheet" href="assets/css/styles.css">
  <style>
    body {
      min-height: 100vh;
    }

    .mgr-container {
      display: flex;
      gap: 2rem;
      padding: 2rem;
    }

    .mgr-main-content {
      flex: 1;
      padding-bottom: 2rem;
    }

    .mgr-title h1 {
      font-size: 2rem;
      margin-bottom: 0.25rem;
    }

    .mgr-title p {
      color: #666;
      margin-bottom: 2rem;
    }

    details {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
      margin-bottom: 1.5rem;
      overflow: hidden;
      transition: all 0.3s ease;
    }

    summary {
      font-weight: 600;
      padding: 1rem 1.5rem;
      background-color: #f8f9fc;
      border-bottom: 1px solid #eee;
      cursor: pointer;
    }

    summary:hover {
      background-color: #e9ecef;
    }

    .bt-form {
      padding: 1.5rem;
      background: #fff;
    }

    .bt-question {
      margin-bottom: 1.5rem;
    }

    .bt-question label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      font-size: 1rem;
      color: black;
    }

    .bt-radios {
      display: flex;
      gap: 1.5rem;
    }

    .bt-radios input[type="radio"] {
      width: 20px;
      height: 20px;
      transform: scale(1.1);
      cursor: pointer;
    }

    .bt-radios label {
      display: flex;
      align-items: center;
      gap: 0.2rem;
      font-size: 1rem;
    }
  </style>
</head>

<body>
  <div class="top-banner" style="
  background: url('./assets/img/kapekapeah.jpg') no-repeat center center / cover;
    width: 100%;
    height: 40%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 0;
">

    <div class="mgr-container">
      <?php include "./php/includes/mgr_sidebar.php"; ?>
      <main class="mgr-main-content">
        <div class="mgr-title">
          <div class="mgr-title">
            <h1>Behavioral Evaluation</h1>
            <p>Week of <?= $weekRange ?> — Evaluate team members with a standardized 10-question score sheet.</p>
          </div>

        </div>

        <?php foreach ($employees as $emp): ?>
          <details>
            <summary><?= htmlspecialchars($emp['full_name']) ?></summary>
            <form class="bt-form" method="POST" action="behavioral_tracker_save.php">
              <input type="hidden" name="employee_id" value="<?= $emp['uid'] ?>">
              <?php foreach ($questions as $q): ?>
                <div class="bt-question">
                  <label for="<?= $q ?>-<?= $emp['uid'] ?>"><?= $q ?></label>

                  <div class="bt-radios">
                    <label><input type="radio" name="<?= $q ?>" id="<?= $q ?>-<?= $emp['uid'] ?>-1" value="1" required>1
                      pt</label>
                    <label><input type="radio" name="<?= $q ?>" id="<?= $q ?>-<?= $emp['uid'] ?>-3" value="3">3 pt</label>
                    <label><input type="radio" name="<?= $q ?>" id="<?= $q ?>-<?= $emp['uid'] ?>-5" value="5">5 pt</label>
                  </div>
                </div>
              <?php endforeach; ?>
              <button type="submit" class="add-task-btn">Submit Evaluation</button>
            </form>
          </details>
        <?php endforeach; ?>
      </main>
    </div>
</body>

</html>