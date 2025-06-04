<?php

include "database/database.php";
function getManagerAllTasks($managerId)
{
  $conn = connect_to_database();
  $tasks = [];

  $sql = "
    SELECT
      t.id AS id,
      assignee.uid AS assignee_id,
      CONCAT(assignee.firstname, ' ', assignee.lastname) AS assignee_name,
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

?>