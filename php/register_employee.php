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
ob_start(); // Optional: buffers any output before JSON

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
  'role' => "employee",
  'job_title' => $_POST['job_title'] ?? null,
  'emergency_contact_name' => $_POST['emergency_name'] ?? null,
  'emergency_relationship' => $_POST['emergency_relation'] ?? null,
  'emergency_phone' => $_POST['emergency_contact'] ?? null,
  'profile_image' => $profilePath
];

$ok = insertUser($data);
if ($ok !== true) {
  // return the actual SQL error
  echo json_encode([
    'success' => false,
    'message' => $ok
  ]);
  exit;
}

$subject = "Welcome to the Company";
$message = "Hi {$data['firstname']},\n\n"
  . "Welcome aboard!\n"
  . "Your Employee ID is: {$data['user_code']}\n"
  . "Your temporary password is: changeme\n\n"
  . "Please log in and change your password.\n\n"
  . "Best regards,\nHR Team";

$emailSent = false;
$mail = new PHPMailer(true);

try {
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'spaghettis002@gmail.com';
  $mail->Password = 'wmfa dwtc agio xcka ';
  $mail->SMTPSecure = 'tls';
  $mail->Port = 587;

  $mail->setFrom('spaghettis002@gmail.com', 'HR Team');
  $mail->addAddress($data['email'], $data['firstname']);

  $mail->isHTML(false);
  $mail->Subject = $subject;
  $mail->Body = $message;

  $mail->send();
  $emailSent = true;
} catch (Exception $e) {
  $emailSent = false;
}

echo json_encode([
  'success' => true,
  'employee_id' => $data['user_code'],
  'email_sent' => $emailSent
]);


exit;
