<?php

include_once __DIR__ . '/database.php';

function insertUser(array $user)
{
  $conn = connect_to_database();
  if (!$conn) {
    return "DB connection failed: " . mysqli_connect_error();
  }

  $required = ['user_code', 'username', 'password', 'firstname', 'lastname', 'email', 'date_of_birth', 'start_date', 'role'];
  foreach ($required as $f) {
    if (empty($user[$f])) {
      return "Missing required field: $f";
    }
  }

  $uc = mysqli_real_escape_string($conn, $user['user_code']);
  $un = mysqli_real_escape_string($conn, $user['username']);
  $pw = mysqli_real_escape_string($conn, $user['password']);
  $fn = mysqli_real_escape_string($conn, $user['firstname']);
  $ln = mysqli_real_escape_string($conn, $user['lastname']);
  $dob = mysqli_real_escape_string($conn, $user['date_of_birth']);
  $pob = isset($user['place_of_birth'])
    ? "'" . mysqli_real_escape_string($conn, $user['place_of_birth']) . "'"
    : "NULL";
  $gen = isset($user['gender'])
    ? "'" . mysqli_real_escape_string($conn, $user['gender']) . "'"
    : "NULL";
  $cs = isset($user['civil_status'])
    ? "'" . mysqli_real_escape_string($conn, $user['civil_status']) . "'"
    : "NULL";
  $nat = isset($user['nationality'])
    ? "'" . mysqli_real_escape_string($conn, $user['nationality']) . "'"
    : "NULL";
  $ph = isset($user['phone'])
    ? "'" . mysqli_real_escape_string($conn, $user['phone']) . "'"
    : "NULL";
  $em = mysqli_real_escape_string($conn, $user['email']);
  $addr = isset($user['address'])
    ? "'" . mysqli_real_escape_string($conn, $user['address']) . "'"
    : "NULL";
  $sd = mysqli_real_escape_string($conn, $user['start_date']);
  $ed = isset($user['end_date'])
    ? "'" . mysqli_real_escape_string($conn, $user['end_date']) . "'"
    : "NULL";
  $role = mysqli_real_escape_string($conn, $user['role']);
  $jt = isset($user['job_title'])
    ? "'" . mysqli_real_escape_string($conn, $user['job_title']) . "'"
    : "NULL";
  $ecn = isset($user['emergency_contact_name'])
    ? "'" . mysqli_real_escape_string($conn, $user['emergency_contact_name']) . "'"
    : "NULL";
  $erel = isset($user['emergency_relationship'])
    ? "'" . mysqli_real_escape_string($conn, $user['emergency_relationship']) . "'"
    : "NULL";
  $eph = isset($user['emergency_phone'])
    ? "'" . mysqli_real_escape_string($conn, $user['emergency_phone']) . "'"
    : "NULL";
  $pi = isset($user['profile_image'])
    ? "'" . mysqli_real_escape_string($conn, $user['profile_image']) . "'"
    : "NULL";

  $sql = "
      INSERT INTO users
        (user_code, username, password,
         firstname, lastname,
         date_of_birth, place_of_birth, gender, civil_status, nationality,
         phone, email, address,
         start_date, end_date, role, job_title,
         emergency_contact_name, emergency_relationship, emergency_phone,
         profile_image
        )
      VALUES
        ('$uc', '$un', '$pw',
         '$fn', '$ln',
         '$dob', $pob, $gen, $cs, $nat,
         $ph, '$em', $addr,
         '$sd', $ed, '$role', $jt,
         $ecn, $erel, $eph,
         $pi
        )
    ";

  if (mysqli_query($conn, $sql)) {
    mysqli_close($conn);
    return true;
  } else {
    $err = mysqli_error($conn);
    error_log("[insertUser] SQL: $sql");
    error_log("[insertUser] Error: $err");
    mysqli_close($conn);
    return "Insert failed: $err";
  }
}

function getManagerAllTasks($managerCode, $limit = null)
{
  $conn = connect_to_database();
  $tasks = [];

  $sql = "
    SELECT
      t.id AS id,
      assignee.user_code AS assignee_code,
      CONCAT(assignee.firstname, ' ', assignee.lastname) AS assignee_name,
      t.template_id,
      tt.description AS task,
      t.start_date,
      t.end_date,
      t.timeline,
      t.status,
      t.priority
    FROM tasks t
    JOIN task_templates tt ON t.template_id = tt.id
    JOIN users assignee ON assignee.user_code = t.assigned_to
    WHERE t.assigned_by = '$managerCode'
  ";

  if ($limit !== null && is_int($limit) && $limit > 0) {
    $sql .= " LIMIT $limit";
  }

  $result = $conn->query($sql);
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $tasks[] = $row;
    }
  }
  return $tasks;
}

function getTasksOfManager($managerCode, $limit = null)
{
  $conn = connect_to_database();
  $tasks = [];

  $sql = "
    SELECT
      t.id AS id,
      assignee.user_code AS assignee_code,
      CONCAT(assignee.firstname, ' ', assignee.lastname) AS assignee_name,
      t.template_id,
      tt.description AS task,
      t.start_date,
      t.end_date,
      t.timeline,
      t.status,
      t.priority
    FROM tasks t
    JOIN task_templates tt ON t.template_id = tt.id
    JOIN users assignee ON assignee.user_code = t.assigned_to
    WHERE t.assigned_to = '$managerCode'
  ";

  if ($limit !== null && is_int($limit) && $limit > 0) {
    $sql .= " LIMIT $limit";
  }

  $result = $conn->query($sql);
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $tasks[] = $row;
    }
  }
  return $tasks;
}

function getEmployeeTasks($employeeCode, $limit = null)
{
  $conn = connect_to_database();
  $tasks = [];

  $sql = "
   SELECT
      t.id AS id,
      manager.user_code AS manager_code,
      CONCAT(manager.firstname, ' ', manager.lastname) AS manager_name,
      tt.description AS task,
      t.start_date,
      t.end_date,
      t.timeline,
      t.status,
      t.priority
    FROM tasks t
    JOIN task_templates tt ON t.template_id = tt.id
    JOIN users manager ON manager.user_code = t.assigned_by
    WHERE t.assigned_to = '$employeeCode'
  ";

  if ($limit !== null && is_int($limit) && $limit > 0) {
    $sql .= " LIMIT $limit";
  }

  $result = $conn->query($sql);
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $tasks[] = $row;
    }
  }

  return $tasks;
}

function getPastTasks()
{
  $con = connect_to_database();
  $taskHistory = [];

  $sql = "
    SELECT
      u.user_code,
      u.firstname,
      u.full_name,
      u.lastname,
      t.status,
      t.timeline,
      t.start_date,
      t.end_date,
      tt.description
    FROM tasks t
    JOIN users u ON t.assigned_to = u.user_code
    JOIN task_templates tt ON t.template_id = tt.id
    WHERE t.status IN ('Done', 'Overdue')
    ORDER BY u.user_code, t.end_date DESC
  ";

  $result = $con->query($sql);
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $userCode = $row['user_code'];
      $fullName = $row['full_name'];

      if (!isset($taskHistory[$userCode])) {
        $taskHistory[$userCode] = [
          'name' => $fullName,
          'tasks' => [],
        ];
      }

      $taskHistory[$userCode]['tasks'][] = [
        'description' => $row['description'],
        'start_date' => $row['start_date'],
        'end_date' => $row['end_date'],
        'timeline' => $row['timeline'],
        'status' => $row['status'],
      ];
    }
  }

  return $taskHistory;
}

function updateTaskStatus($taskId, $status)
{
  $conn = connect_to_database();
  $sql = "UPDATE tasks SET status = '$status' WHERE id = $taskId";
  return $conn->query($sql);
}

function updateTask($taskId, $templateId, $startDate, $endDate, $priority)
{
  $conn = connect_to_database();

  $sql = "UPDATE tasks
          SET template_id = '$templateId',
              start_date = '$startDate',
              end_date = '$endDate',
              priority = '$priority'
          WHERE id = $taskId";

  if ($conn->query($sql) === TRUE) {
    return true;
  } else {
    return "Error updating task: " . $conn->error;
  }
}

function getTaskById($taskId)
{
  $conn = connect_to_database();
  $sql = "
    SELECT
      t.id AS id,
      assignee.user_code AS assignee_code,
      CONCAT(assignee.firstname, ' ', assignee.lastname) AS assignee_name,
      t.template_id,
      tt.description AS task,
      t.start_date,
      t.end_date,
      t.timeline,
      t.status,
      t.priority
    FROM tasks t
    JOIN task_templates tt ON t.template_id = tt.id
    JOIN users assignee ON assignee.user_code = t.assigned_to
    WHERE t.id = $taskId
  ";
  $result = $conn->query($sql);
  return $result->fetch_assoc();
}

function createTask($managerCode, $assigneeCode, $templateId, $startDate, $endDate, $priority)
{
  $conn = connect_to_database();

  $days = (new DateTime($startDate))->diff(new DateTime($endDate))->days + 1;
  $timeline = $days . ' day' . ($days > 1 ? 's' : '');

  $templateId = (int) $templateId;
  $startDate = $conn->real_escape_string($startDate);
  $endDate = $conn->real_escape_string($endDate);
  $priority = $conn->real_escape_string($priority);
  $timeline = $conn->real_escape_string($timeline);

  $sql = "INSERT INTO tasks
            (assigned_by, assigned_to, template_id, timeline, start_date, end_date, priority)
          VALUES
            ('$managerCode', '$assigneeCode', $templateId, '$timeline', '$startDate', '$endDate', '$priority')";


  if ($conn->query($sql) === TRUE) {
    return $conn->insert_id; // Return the new task's ID
  } else {
    return "Error creating task: " . $conn->error;
  }
}



function deleteTask($taskId)
{
  $conn = connect_to_database();
  $sql = "DELETE FROM tasks WHERE id = $taskId";
  if ($conn->query($sql) === TRUE) {
    return true;
  } else {
    return "Error deleting task: " . $conn->error;
  }
}




function getAllEmployees()
{
  $conn = connect_to_database();
  $sql = "SELECT user_code, full_name FROM users WHERE role = 'employee'";
  $result = $conn->query($sql);
  $employees = [];
  while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
  }
  return $employees;
}

function getAllManagers()
{
  $conn = connect_to_database();
  $sql = "SELECT user_code, full_name FROM users WHERE role = 'manager'";
  $result = $conn->query($sql);
  $employees = [];
  while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
  }
  return $employees;
}

function getTemplateDescription($templateId)
{
  $conn = connect_to_database();
  $sql = "SELECT description FROM task_templates WHERE id = $templateId";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  return $row['description'];
}

function getAllTaskTemplates()
{
  $conn = connect_to_database();
  $taskTemplates = [];
  $sql = "SELECT id, description FROM task_templates ORDER BY description ASC";

  $result = $conn->query($sql);
  while ($row = $result->fetch_assoc()) {
    $taskTemplates[] = $row;
  }
  return $taskTemplates;
}


function getAllUserNotes($userCode)
{
  $conn = connect_to_database();
  $notes = [];

  if (!$conn || !$userCode) {
    return $notes;
  }

  $sql = "
    SELECT id, title, content, alarm_time, created_at
    FROM notes
    WHERE user_code = '$userCode'
    ORDER BY id DESC
  ";

  $result = $conn->query($sql);
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $notes[] = $row;
    }
  }

  return $notes;
}

function saveNote($user_code, $title, $content, $alarm = null, $noteId = null)
{
  $conn = connect_to_database();
  if (!$conn) {
    return ['success' => false, 'message' => 'Database connection failed'];
  }

  $title = mysqli_real_escape_string($conn, trim($title));
  $content = mysqli_real_escape_string($conn, trim($content));
  $alarm = $alarm ? mysqli_real_escape_string($conn, $alarm) : null;
  $noteId = $noteId !== null ? intval($noteId) : null;

  if (empty($title) || empty($content)) {
    return ['success' => false, 'message' => 'Title and content are required'];
  }

  if ($noteId) {
    // Update existing note
    $sql = "UPDATE notes
                SET title = '$title', content = '$content', alarm_time = " . ($alarm ? "'$alarm'" : "NULL") . "
                WHERE id = $noteId AND user_code = '$user_code'";
  } else {
    // Insert new note
    $sql = "INSERT INTO notes (user_code, title, content, alarm_time)
                VALUES ('$user_code', '$title', '$content', " . ($alarm ? "'$alarm'" : "NULL") . ")";
  }

  if (mysqli_query($conn, $sql)) {
    mysqli_close($conn);
    return ['success' => true];
  } else {
    $error = mysqli_error($conn);
    mysqli_close($conn);
    return ['success' => false, 'message' => "Error saving note: $error"];
  }
}

function deleteNote($user_code, $noteId)
{
  $conn = connect_to_database();
  if (!$conn) {
    return ['success' => false, 'message' => 'Database connection failed'];
  }

  $noteId = intval($noteId);

  // Verify ownership before deleting
  $checkSql = "SELECT user_code FROM notes WHERE id = $noteId LIMIT 1";
  $result = $conn->query($checkSql);
  if (!$result) {
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Failed to verify note ownership'];
  }

  $row = $result->fetch_assoc();
  if (!$row || $row['user_code'] != $user_code) {  // <-- Changed here
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Unauthorized to delete this note'];
  }

  // Proceed with delete
  $deleteSql = "DELETE FROM notes WHERE id = $noteId";
  if ($conn->query($deleteSql)) {
    mysqli_close($conn);
    return ['success' => true];
  } else {
    $error = $conn->error;
    mysqli_close($conn);
    return ['success' => false, 'message' => "Error deleting note: $error"];
  }
}

function getAllUsers()
{
  $conn = connect_to_database();
  if (!$conn) {
    return [];
  }
  $sql = "SELECT full_name, job_title FROM users";
  $result = $conn->query($sql);
  if (!$result) {
    return [];
  }
  $users = [];
  while ($row = $result->fetch_assoc()) {
    $users[] = $row;
  }
  return $users;
}

function getEvaluation($employeeCode, string $weekStartDate, $managerCode)
{
  $conn = connect_to_database();

  $weekStartEsc = mysqli_real_escape_string($conn, $weekStartDate);

  $sql = "SELECT * FROM behavioral_evaluations
            WHERE employee_code = '$employeeCode'
              AND evaluated_by = '$managerCode'
              AND week_start_date = '$weekStartEsc'
            LIMIT 1";

  $result = mysqli_query($conn, $sql);
  if ($result && mysqli_num_rows($result) > 0) {
    return mysqli_fetch_assoc($result);
  }
  return false;
}
function saveOrUpdateScore($employeeCode, $managerCode, $totalScore, $feedback, $weekStartDate, $detailsJson)
{
  $conn = connect_to_database();

  // Escape inputs & sanitize
  $totalScore = intval($totalScore);
  $weekStartDateEsc = $conn->real_escape_string($weekStartDate);
  // week_end_date can be computed as Sunday of that week
  $weekEndDate = date('Y-m-d', strtotime($weekStartDate . ' +6 days'));
  $weekEndDateEsc = $conn->real_escape_string($weekEndDate);
  $scoresEsc = $conn->real_escape_string($detailsJson);

  // Check if evaluation exists already
  $existing = getEvaluation($employeeCode, $weekStartDate, $managerCode);  // Make sure this function checks behavioral_evaluations table using evaluated_by=managerId!

  if ($existing) {
    $sql = "UPDATE behavioral_evaluations SET
                    total_score = $totalScore,
                    scores = '$scoresEsc',
                    week_end_date = '$weekEndDateEsc'
                WHERE employee_code = '$employeeCode'
                  AND week_start_date = '$weekStartDateEsc'
                  AND evaluated_by = '$managerCode'";
  } else {
    // Insert new record
    $sql = "INSERT INTO behavioral_evaluations
                (employee_code, evaluated_by, week_start_date, week_end_date, scores, total_score) VALUES
                ('$employeeCode', '$managerCode', '$weekStartDateEsc', '$weekEndDateEsc', '$scoresEsc', $totalScore)";
  }

  if ($conn->query($sql)) {
    return ['success' => true];
  } else {
    return ['success' => false, 'message' => $conn->error];
  }
}

function getMonthlyEvaluationScores($startDate, $endDate)
{
  $conn = connect_to_database();
  $evaluations = [];

  // Escape and quote the dates
  $start = $conn->real_escape_string($startDate);
  $end = $conn->real_escape_string($endDate);

  $sql = "
    SELECT e.employee_code, u.full_name,
           SUM(e.total_score) AS total_score,
           COUNT(*) AS eval_count
    FROM behavioral_evaluations e
    JOIN users u ON e.employee_code = u.user_code
    WHERE e.week_start_date BETWEEN '$start' AND '$end'
    GROUP BY e.employee_code
    ORDER BY total_score DESC
  ";

  $result = $conn->query($sql);

  if (!$result) {
    die("Query error: " . $conn->error);
  }

  while ($row = $result->fetch_assoc()) {
    $evaluations[] = $row;
  }

  return $evaluations;
}
