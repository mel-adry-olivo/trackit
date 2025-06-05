<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../php/PHPMailer/src/Exception.php';
require '../php/PHPMailer/src/PHPMailer.php';
require '../php/PHPMailer/src/SMTP.php';

header('Content-Type: application/json');
ob_start();

session_start();
include '../database/queries.php';

$required = ['id_number', 'first_name', 'last_name', 'email', 'dob', 'start_date'];
foreach ($required as $f) {
  if (empty($_POST[$f])) {
    echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $f)) . " is required"]);
    exit;
  }
}

$profilePath = null;
if (!empty($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
  $dir = __DIR__ . '/uploads/profile/';
  if (!is_dir($dir))
    mkdir($dir, 0755, true);
  $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
  $code = $_POST['id_number'];
  $dst = $dir . "{$code}.{$ext}";
  if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $dst)) {
    $profilePath = "uploads/profile/{$code}.{$ext}";
  } else {
    echo json_encode(['success' => false, 'message' => 'Could not save profile image']);
    exit;
  }
}

$data = [
  'user_code' => $_POST['id_number'],
  'username' => $_POST['email'],
  'password' => password_hash('changeme', PASSWORD_DEFAULT),
  'firstname' => $_POST['first_name'],
  'lastname' => $_POST['last_name'],
  'email' => $_POST['email'],
  'date_of_birth' => $_POST['dob'],
  'place_of_birth' => $_POST['birth_place'] ?? null,
  'gender' => $_POST['gender'],
  'civil_status' => $_POST['civil_status'] ?? null,
  'nationality' => $_POST['nationality'] ?? null,
  'phone' => $_POST['phone_number'] ?? null,
  'address' => $_POST['address'] ?? null,
  'start_date' => $_POST['start_date'],
  'end_date' => $_POST['date_created'] ?? null,
  'role' => "manager",
  'job_title' => $_POST['job_title'] ?? null,
  'emergency_contact_name' => $_POST['emergency_name'] ?? null,
  'emergency_relationship' => $_POST['emergency_relation'] ?? null,
  'emergency_phone' => $_POST['emergency_contact'] ?? null,
  'profile_image' => $profilePath
];

$ok = insertUser($data);
if ($ok !== true) {
  echo json_encode([
    'success' => false,
    'message' => $ok,
    'data' => $data
  ]);
  exit;
}

$subject = "Welcome to Paul Kaldi!";
$message = "Dear {$data['firstname']} {$data['lastname']},\n\n"
  . "Congratulations and welcome to the team at Paul Kaldi! We’re thrilled to have you join us and look forward to the contributions you'll bring to our growing team.\n\n"
  . "Below are your account credentials to access the Paul Kaldi Manager Dashboard:\n\n"
  . "Full Name: {$data['firstname']} {$data['lastname']}\n"
  . "ID Number: {$data['user_code']}\n"
  . "Temporary Password: changeme\n\n"
  . "Dashboard Login: http://localhost/trackit/login.php\n\n"
  . "Important: Please make sure to change your password upon your first login to ensure your account’s security.\n\n"
  . "Note: This is an automated message. Please do not reply to this email.\n\n"
  . "Welcome aboard once again!\n\n"
  . "Best regards,\n"
  . "Paul Kaldi Team";

$emailSent = false;
$mail = new PHPMailer(true);

try {
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'paulkaldi2025@gmail.com';
  $mail->Password = 'pivh mzdc otlv dpoq';
  $mail->SMTPSecure = 'tls';
  $mail->Port = 587;

  $mail->setFrom('paulkaldi2025@gmail.com', 'HR Team');
  $mail->addAddress($data['email'], $data['firstname']);

  $mail->isHTML(false);
  $mail->Subject = $subject;
  $mail->Body = $message;

  $mail->send();
  $emailSent = true;
} catch (Exception $e) {
  echo json_encode([
    'success' => true,
    'employee_id' => $data['user_code'],
    'email_sent' => false,
    'error' => $mail->ErrorInfo
  ]);
  exit;
}

echo json_encode([
  'success' => true,
  'employee_id' => $data['user_code'],
  'email_sent' => $emailSent
]);

exit;
