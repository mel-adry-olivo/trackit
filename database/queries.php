<?php

include "database/database.php";
function getManagerAllTasks($managerId, $limit = null)
{
  $conn = connect_to_database();
  $tasks = [];

  $sql = "
    SELECT
      t.id AS id,
      assignee.uid AS assignee_id,
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
    JOIN users assignee ON assignee.uid = t.assigned_to
    WHERE t.assigned_by = $managerId
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
function getEmployeeTasks($employeeId, $limit = null)
{
  $conn = connect_to_database();
  $tasks = [];

  $sql = "
    SELECT
      t.id AS id,
      manager.uid AS manager_id,
      CONCAT(manager.firstname, ' ', manager.lastname) AS manager_name,
      tt.description AS task,
      t.start_date,
      t.end_date,
      t.timeline,
      t.status,
      t.priority
    FROM tasks t
    JOIN task_templates tt ON t.template_id = tt.id
    JOIN users manager ON manager.uid = t.assigned_by
    WHERE t.assigned_to = $employeeId
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
      u.uid,
      u.firstname,
      u.lastname,
      t.status,
      t.timeline,
      t.start_date,
      t.end_date,
      tt.description
    FROM tasks t
    JOIN users u ON t.assigned_to = u.uid
    JOIN task_templates tt ON t.template_id = tt.id
    WHERE t.status IN ('Done', 'Overdue')
    ORDER BY u.uid, t.end_date DESC
  ";

  $result = $con->query($sql);
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $uid = $row['uid'];
      $fullName = $row['firstname'] . ' ' . $row['lastname'];

      if (!isset($taskHistory[$uid])) {
        $taskHistory[$uid] = [
          'name' => $fullName,
          'tasks' => [],
        ];
      }

      $taskHistory[$uid]['tasks'][] = [
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
      assignee.uid AS assignee_id,
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
    JOIN users assignee ON assignee.uid = t.assigned_to
    WHERE t.id = $taskId
  ";
  $result = $conn->query($sql);
  return $result->fetch_assoc();
}

function createTask($managerId, $assigneeId, $templateId, $startDate, $endDate, $priority)
{
  $conn = connect_to_database();

  // Calculate timeline string (e.g., "3 days")
  $days = (new DateTime($startDate))->diff(new DateTime($endDate))->days + 1;
  $timeline = $days . ' day' . ($days > 1 ? 's' : '');

  // Escape values manually (be very careful in real apps)
  $managerId = (int) $managerId;
  $assigneeId = (int) $assigneeId;
  $templateId = (int) $templateId;
  $startDate = $conn->real_escape_string($startDate);
  $endDate = $conn->real_escape_string($endDate);
  $priority = $conn->real_escape_string($priority);
  $timeline = $conn->real_escape_string($timeline);

  // Build query
  $sql = "INSERT INTO tasks
            (assigned_by, assigned_to, template_id, timeline, start_date, end_date, priority)
          VALUES
            ($managerId, $assigneeId, $templateId, '$timeline', '$startDate', '$endDate', '$priority')";

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
  $sql = "SELECT uid, full_name FROM users WHERE role = 'employee'";
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


function getAllUserNotes($userId)
{
  $conn = connect_to_database();
  $notes = [];

  if (!$conn || !$userId) {
    return $notes;
  }

  $sql = "
    SELECT id, title, content, alarm_time, created_at
    FROM notes
    WHERE user_id = $userId
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

function saveNote($userId, $title, $content, $alarm = null, $noteId = null)
{
  $conn = connect_to_database();
  if (!$conn) {
    return ['success' => false, 'message' => 'Database connection failed'];
  }

  // Basic sanitization
  $userId = intval($userId);
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
                WHERE id = $noteId AND user_id = $userId";
  } else {
    // Insert new note
    $sql = "INSERT INTO notes (user_id, title, content, alarm_time)
                VALUES ('$userId', '$title', '$content', " . ($alarm ? "'$alarm'" : "NULL") . ")";
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

function deleteNote($userId, $noteId)
{
  $conn = connect_to_database();
  if (!$conn) {
    return ['success' => false, 'message' => 'Database connection failed'];
  }

  $userId = intval($userId);
  $noteId = intval($noteId);

  // Verify ownership before deleting
  $checkSql = "SELECT user_id FROM notes WHERE id = $noteId LIMIT 1";
  $result = $conn->query($checkSql);
  if (!$result) {
    mysqli_close($conn);
    return ['success' => false, 'message' => 'Failed to verify note ownership'];
  }

  $row = $result->fetch_assoc();
  if (!$row || intval($row['user_id']) != $userId) {  // <-- Changed here
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
