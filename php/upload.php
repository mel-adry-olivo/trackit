<?php
session_start();
include "../database/database.php";

$redirectBack = $_SERVER['HTTP_REFERER'] ?? '../login.php';
$userCode = $_SESSION['user_code'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_image'])) {

  if (!$userCode) {
    die('Unauthorized');
  }

  if (!isset($_FILES['profile_image'])) {
    $_SESSION['error_message'] = "No file uploaded.";
    header("Location: $redirectBack");
    exit();
  }

  $fileError = $_FILES['profile_image']['error'];
  if ($fileError !== UPLOAD_ERR_OK) {
    $uploadErrors = [
      UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload_max_filesize directive in php.ini.",
      UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form.",
      UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded.",
      UPLOAD_ERR_NO_FILE => "No file was uploaded.",
      UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder.",
      UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
      UPLOAD_ERR_EXTENSION => "File upload stopped by a PHP extension.",
    ];
    $_SESSION['error_message'] = "Upload failed: " . ($uploadErrors[$fileError] ?? "Unknown upload error.");
    header("Location: $redirectBack");
    exit();
  }

  $fileTmpPath = $_FILES['profile_image']['tmp_name'];
  $fileName = $_FILES['profile_image']['name'];
  $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

  $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];
  if (!in_array($fileExtension, $allowedfileExtensions)) {
    $_SESSION['error_message'] = "Upload failed. Allowed file types: " . implode(', ', $allowedfileExtensions);
    header("Location: $redirectBack");
    exit();
  }

  $uploadFileDir = '../uploads/';
  if (!is_dir($uploadFileDir) && !mkdir($uploadFileDir, 0755, true)) {
    $_SESSION['error_message'] = "Failed to create upload directory.";
    header("Location: $redirectBack");
    exit();
  }

  $newFileName = 'profile_' . $userCode . '_' . uniqid() . '.' . $fileExtension;
  $dest_path = $uploadFileDir . $newFileName;

  if (!move_uploaded_file($fileTmpPath, $dest_path)) {
    $_SESSION['error_message'] = "Error moving uploaded file. Check permissions.";
    header("Location: $redirectBack");
    exit();
  }

  $conn = connect_to_database();
  $newFileNameEscaped = mysqli_real_escape_string($conn, $newFileName);
  $sql = "UPDATE users SET profile_image = '$newFileNameEscaped' WHERE user_code = '$userCode'";

  if (mysqli_query($conn, $sql)) {
    $_SESSION['profile_image'] = $newFileName;
    $_SESSION['success_message'] = "Profile picture updated successfully.";
  } else {
    $_SESSION['error_message'] = "Database update failed: " . mysqli_error($conn);
    unlink($dest_path);
  }
  mysqli_close($conn);

  header("Location: $redirectBack");
  exit();
}
