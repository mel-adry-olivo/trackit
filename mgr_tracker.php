<?php
session_start();
include "database/queries.php";

if ($_SESSION['role'] !== 'manager') {
  header('HTTP/1.1 403 Forbidden');
  exit('Access denied.');
}

// Get current week's Monday (for DB key)
$weekStartDate = date('Y-m-d', strtotime('monday this week'));
$startOfWeekDisplay = date('F j, Y', strtotime($weekStartDate));
$endOfWeekDisplay = date('F j, Y', strtotime('sunday this week'));
$weekRange = "$startOfWeekDisplay – $endOfWeekDisplay";

// Standard questions
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

$managerId = $_SESSION['uid'];
$submissionMessage = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $employeeId = isset($_POST['employee_id']) ? intval($_POST['employee_id']) : 0;

  if (!$employeeId) {
    $submissionMessage = "Invalid employee ID.";
  } else {
    $answers = $_POST['answers'][$employeeId] ?? [];

    // Count how many questions were answered
    if (count($answers) !== count($questions)) {
      $submissionMessage = "All questions must be answered.";
    } else {
      $totalScore = 0;
      foreach ($questions as $q) {
        if (!isset($answers[$q])) {
          $submissionMessage = "All questions must be answered.";
          break;
        }
        $totalScore += intval($answers[$q]);
      }
      if (!$submissionMessage) {
        $feedback = "Evaluation completed for week of $startOfWeekDisplay";
        $detailsJson = json_encode($answers);

        $result = saveOrUpdateScore($employeeId, $managerId, $totalScore, $feedback, $weekStartDate, $detailsJson);

        if ($result['success']) {
          $submissionMessage = "✅ Evaluation successfully submitted.";
        } else {
          $submissionMessage = "❌ Failed to save score: " . $result['message'];
        }
      }
    }
  }
}


// Load employees list
$employees = getAllEmployees();

function getExistingEvaluationAnswers($employeeId, $weekStartDate, $managerId)
{
  $eval = getEvaluation($employeeId, $weekStartDate, $managerId);
  if (!$eval)
    return [];
  return json_decode($eval['scores'], true) ?: [];
}


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
  background: url('./assets/img/kapekapeah.jpg') no-repeat center center / cover;
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
          <h1>Behavioral Evaluation</h1>
          <p>Week of <?= htmlspecialchars($weekRange) ?> — Evaluate team members with a standardized 10-question score
            sheet.</p>
        </div>
        <div class="mgr-branch-info">
          <span>Branch: <strong>WVSU-BINHI TBI</strong> | Time: </span>
          <span id="clock">--:--:--</span>
        </div>
      </div>

      <?php if ($submissionMessage): ?>
        <div class="status-message"><?= htmlspecialchars($submissionMessage) ?></div>
      <?php endif; ?>

      <?php foreach ($employees as $emp):
        $summaryClass = "";
        $existingEval = getEvaluation($emp['uid'], $weekStartDate, $managerId);
        $existingAnswers = $existingEval ? json_decode($existingEval['scores'], true) : [];

        // Change label based on whether evaluation exists
        $summaryLabel = htmlspecialchars($emp['full_name']);
        if ($existingEval) {
          $summaryLabel .= " (Evaluated)";
          $summaryClass = "evaluated";
        }
        ?>
        <details>
          <summary class="<?= $summaryClass ?>"><?= $summaryLabel ?></summary>
          <form class="bt-form" method="POST" action="">
            <input type="hidden" name="employee_id" value="<?= $emp['uid'] ?>" />
            <?php foreach ($questions as $q):
              $savedValue = $existingAnswers[$q] ?? null;
              ?>
              <div class="bt-question">
                <label for="<?= htmlspecialchars($q) ?>-<?= $emp['uid'] ?>"><?= htmlspecialchars($q) ?></label>
                <div class="bt-radios">
                  <label>
                    <input type="radio" name="answers[<?= $emp['uid'] ?>][<?= htmlspecialchars($q) ?>]" value="1"
                      <?= ($savedValue == 1) ? 'checked' : '' ?> required />
                    1pt
                  </label>
                  <label>
                    <input type="radio" name="answers[<?= $emp['uid'] ?>][<?= htmlspecialchars($q) ?>]" value="3"
                      <?= ($savedValue == 3) ? 'checked' : '' ?> required />
                    3pt
                  </label>
                  <label>
                    <input type="radio" name="answers[<?= $emp['uid'] ?>][<?= htmlspecialchars($q) ?>]" value="5"
                      <?= ($savedValue == 5) ? 'checked' : '' ?> />
                    5pt
                  </label>
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