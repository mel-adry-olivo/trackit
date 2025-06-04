<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Include the database connection
require_once 'db_connection.php';

// Include PHPMailer
require_once __DIR__ . '/sendemail/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/sendemail/phpmailer/src/SMTP.php';
require_once __DIR__ . '/sendemail/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Start transaction
        $conn->autocommit(FALSE);

        // Get and validate form data
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $date_of_birth = $_POST['dob'] ?? '';
        $phone_number = trim($_POST['phone_number'] ?? '') ?: null;
        $email = trim($_POST['email'] ?? '');
        $job_title = trim($_POST['department'] ?? '') ?: null;
        $role = $_POST['role'] ?? 'Staff';
        $dept_id = 'D001'; // Default department
        $start_date = $_POST['start_date'] ?? date('Y-m-d');
        $date_created = $_POST['date_created'] ?? date('Y-m-d');

        // Additional fields
        $gender = $_POST['gender'] ?? 'Other';
        $civil_status = $_POST['civil_status'] ?? null;
        $nationality = trim($_POST['nationality'] ?? '') ?: null;
        $address = trim($_POST['address'] ?? '') ?: null;
        $birth_place = trim($_POST['birth_place'] ?? '') ?: null;

        // Emergency contact
        $emergency_name = trim($_POST['emergency_name'] ?? '') ?: null;
        $emergency_relation = trim($_POST['emergency_relation'] ?? '') ?: null;
        $emergency_contact = trim($_POST['emergency_contact'] ?? '') ?: null;

        // Notes
        $notes = trim($_POST['notes'] ?? '') ?: null;

        // Validate required fields
        $errors = [];
        if (empty($first_name)) $errors[] = 'First name is required';
        if (empty($last_name)) $errors[] = 'Last name is required';
        if (empty($email)) $errors[] = 'Email is required';
        if (empty($date_of_birth)) $errors[] = 'Date of birth is required';
        if (empty($gender)) $errors[] = 'Gender is required';
        if (empty($role)) $errors[] = 'Role is required';
        if (empty($start_date)) $errors[] = 'Start date is required';
        if (empty($date_created)) $errors[] = 'Date created is required';

        if (!empty($errors)) {
            throw new Exception(implode('; ', $errors));
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        // Check if email already exists
        $stmt = $conn->prepare("SELECT Emp_Id FROM employees WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception('Email address already exists in the system');
        }
        $stmt->close();

        // Generate employee ID and password
        $emp_id = generateEmployeeId($role, $conn);
        $temp_password = generateRandomPassword(8);
        $hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);

        // Handle profile image upload
        $profile_picture_path = null;
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
            $profile_picture_path = handleProfileImageUpload($_FILES['profile_image'], $emp_id);
        }

        // Map role for employees table (compatible with enum)
        $employee_role = match($role) {
            'POS Manager' => 'Manager',
            'Barista', 'Staff', 'Rider' => 'Employee',
            default => 'Employee'
        };

        // Insert into employees table
        $stmt = $conn->prepare("INSERT INTO employees (Emp_Id, password, first_name, last_name, date_of_birth, phone_number, email, job_title, role, Dept_Id, created_at) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssssssss", $emp_id, $hashed_password, $first_name, $last_name, $date_of_birth, $phone_number, $email, $job_title, $employee_role, $dept_id);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to insert employee: ' . $stmt->error);
        }
        $stmt->close();

        // Insert into employee_profile
        $stmt = $conn->prepare("INSERT INTO employee_profile (Emp_Id, First_Name, Last_Name, Place_of_Birth, Date_of_Birth, Gender, Civil_Status, Nationality, Phone, Email, Address, profile_picture) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssss", $emp_id, $first_name, $last_name, $birth_place, $date_of_birth, $gender, $civil_status, $nationality, $phone_number, $email, $address, $profile_picture_path);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to insert employee profile: ' . $stmt->error);
        }
        $stmt->close();

        // Insert into employment info
        $stmt = $conn->prepare("INSERT INTO employee_employment_information (Emp_Id, Hired_Date, Role, Job_Title) 
                              VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $emp_id, $start_date, $role, $job_title);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to insert employment information: ' . $stmt->error);
        }
        $stmt->close();

        // Insert emergency contact if provided
        if (!empty($emergency_name)) {
            $stmt = $conn->prepare("INSERT INTO emergency_contacts (emp_id, contact_name, relationship, contact_number) 
                                  VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $emp_id, $emergency_name, $emergency_relation, $emergency_contact);
            
            if (!$stmt->execute()) {
                throw new Exception('Failed to insert emergency contact: ' . $stmt->error);
            }
            $stmt->close();
        }

        // Commit transaction before sending email
        $conn->commit();

        // Send welcome email
        $emailResult = sendWelcomeEmail($email, $first_name, $last_name, $emp_id, $temp_password);

        // Log the email result for debugging
        error_log("Email result: " . json_encode($emailResult));

        echo json_encode([
            'success' => true,
            'message' => 'Employee registered successfully' . ($emailResult['success'] ? ' and welcome email sent' : ' but email failed to send'),
            'employee_id' => $emp_id,
            'email_sent' => $emailResult['success'],
            'email_message' => $emailResult['message']
        ]);

    } catch (Exception $e) {
        // Rollback transaction
        if ($conn && !$conn->autocommit(TRUE)) {
            $conn->rollback();
        }
        
        error_log("Registration error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
        
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
            'debug' => [
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ]
        ]);
    } finally {
        if ($conn) {
            $conn->autocommit(TRUE);
        }
    }
    exit;
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

function generateEmployeeId($role, $conn) {
    $prefix = 'PK';
    
    // Map role to suffix
    $roleSuffix = match($role) {
        'Admin' => 'A',
        'POS Manager' => 'M',
        default => 'E'
    };

    // Get the highest existing ID for this role type
    $likePattern = $prefix . $roleSuffix . '%';
    $stmt = $conn->prepare("SELECT Emp_Id FROM employees WHERE Emp_Id LIKE ? ORDER BY Emp_Id DESC LIMIT 1");
    $stmt->bind_param("s", $likePattern);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Extract the number from the ID (e.g., PKE001 -> 001, PKM002 -> 002)
        preg_match('/PK[A-Z](\d+)/', $row['Emp_Id'], $matches);
        $lastNum = isset($matches[1]) ? (int)$matches[1] : 0;
        $nextNum = $lastNum + 1;
    } else {
        $nextNum = 1;
    }
    
    $stmt->close();
    return $prefix . $roleSuffix . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
}

function generateRandomPassword($length = 8) {
    $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lowercase = 'abcdefghijklmnopqrstuvwxyz';
    $numbers = '0123456789';
    $special = '!@#$%';
    
    // Ensure password has at least one of each type
    $password = '';
    $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
    $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
    $password .= $numbers[random_int(0, strlen($numbers) - 1)];
    $password .= $special[random_int(0, strlen($special) - 1)];
    
    // Fill the rest randomly
    $allChars = $uppercase . $lowercase . $numbers . $special;
    for ($i = 4; $i < $length; $i++) {
        $password .= $allChars[random_int(0, strlen($allChars) - 1)];
    }
    
    // Shuffle the password
    return str_shuffle($password);
}

function handleProfileImageUpload($file, $emp_id) {
    $upload_dir = 'uploads/profiles/';
    
    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0755, true)) {
            throw new Exception('Failed to create upload directory');
        }
    }
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = $emp_id . '_' . time() . '.' . $file_extension;
    $filepath = $upload_dir . $filename;
    
    // Validate file type
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($file_extension, $allowed_types)) {
        throw new Exception('Invalid file type. Only JPG, PNG, and GIF are allowed.');
    }
    
    // Validate file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception('File too large. Maximum size is 5MB.');
    }
    
    // Validate image
    if (!getimagesize($file['tmp_name'])) {
        throw new Exception('Invalid image file.');
    }
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $filepath;
    } else {
        throw new Exception('Failed to upload profile image.');
    }
}

function sendWelcomeEmail($recipientEmail, $firstName, $lastName, $employeeId, $tempPassword) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'paulkaldi2025@gmail.com'; // Your Gmail address
        $mail->Password = 'fhqy tudg oifx sftw'; // Your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->SMTPDebug = 0; // Disable debug output in production
        $mail->Debugoutput = 'error_log';

        // Recipients
        $mail->setFrom('paulkaldi2025@gmail.com', 'Paul Kaldi TrackIT System');
        $mail->addAddress($recipientEmail, "$firstName $lastName");
        $mail->addReplyTo('paulkaldi2025@gmail.com', 'Paul Kaldi TrackIT System');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to Paul Kaldi - Your Account Details';
        $mail->Body = generateEmailTemplate($firstName, $lastName, $employeeId, $tempPassword);
        
        // Alternative plain text version
        $mail->AltBody = "Welcome to Paul Kaldi!\n\n" .
                        "Dear $firstName,\n\n" .
                        "Your employee account has been created successfully.\n\n" .
                        "Employee ID: $employeeId\n" .
                        "Temporary Password: $tempPassword\n\n" .
                        "Please log in to the TrackIT system and change your password immediately.\n\n" .
                        "Best regards,\nPaul Kaldi Management Team";

        $result = $mail->send();
        
        return [
            'success' => true, 
            'message' => 'Welcome email sent successfully to ' . $recipientEmail
        ];
        
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        error_log("Exception: " . $e->getMessage());
        
        return [
            'success' => false, 
            'message' => 'Email failed to send: ' . $e->getMessage(),
            'smtp_error' => $mail->ErrorInfo
        ];
    }
}

function generateEmailTemplate($firstName, $lastName, $employeeId, $tempPassword) {
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Welcome to Paul Kaldi</title>
    </head>
    <body style='margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f8f9fa;'>
        <div style='max-width: 600px; margin: 0 auto; background: #f8f9fa; padding: 20px;'>
            <div style='background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                <div style='text-align: center; margin-bottom: 30px;'>
                    <h1 style='color: #2c3e50; margin: 0; font-size: 28px;'>Welcome to Paul Kaldi!</h1>
                    <p style='color: #666; font-size: 16px; margin: 10px 0 0 0;'>TrackIT Employee Management System</p>
                </div>
                
                <p style='font-size: 16px; line-height: 1.6; color: #333;'>Dear $firstName $lastName,</p>
                <p style='font-size: 16px; line-height: 1.6; color: #333;'>We're excited to welcome you to the Paul Kaldi team! Your employee account has been created successfully in our TrackIT system.</p>
                
                <div style='background: linear-gradient(135deg, #28a745, #20c997); padding: 25px; border-radius: 10px; margin: 25px 0; color: white;'>
                    <h3 style='margin: 0 0 15px 0; font-size: 20px;'>🔐 Your Login Credentials</h3>
                    <div style='background: rgba(255,255,255,0.2); padding: 15px; border-radius: 8px; margin: 10px 0;'>
                        <p style='margin: 5px 0; font-size: 16px;'><strong>Employee ID:</strong> <span style='font-family: monospace; font-size: 18px; background: rgba(255,255,255,0.3); padding: 4px 8px; border-radius: 4px;'>$employeeId</span></p>
                        <p style='margin: 5px 0; font-size: 16px;'><strong>Temporary Password:</strong> <span style='font-family: monospace; font-size: 18px; background: rgba(255,255,255,0.3); padding: 4px 8px; border-radius: 4px;'>$tempPassword</span></p>
                    </div>
                </div>
                
                <div style='background: #fff3cd; padding: 20px; border-radius: 8px; margin: 25px 0; border-left: 4px solid #ffc107;'>
                    <p style='margin: 0; color: #856404; font-size: 16px;'><strong>⚠️ Important Security Notice:</strong><br>
                    Please log in to the TrackIT system and change your password immediately for security purposes. Your temporary password is only for initial access.</p>
                </div>
                
                <div style='background: #e7f3ff; padding: 20px; border-radius: 8px; margin: 25px 0; border-left: 4px solid #007bff;'>
                    <h4 style='margin: 0 0 10px 0; color: #004085;'>📋 Next Steps:</h4>
                    <ol style='margin: 0; padding-left: 20px; color: #004085;'>
                        <li>Log in to the TrackIT system using your credentials</li>
                        <li>Change your temporary password</li>
                        <li>Complete your profile information</li>
                        <li>Familiarize yourself with the system features</li>
                    </ol>
                </div>
                
                <p style='font-size: 16px; line-height: 1.6; color: #333;'>If you have any questions or need assistance accessing the system, please don't hesitate to contact your manager or the IT support team.</p>
                
                <div style='text-align: center; margin-top: 40px; padding-top: 20px; border-top: 2px solid #eee;'>
                    <p style='margin: 0; color: #666; font-size: 16px;'>Welcome aboard! ☕</p>
                    <p style='margin: 10px 0 0 0; color: #2c3e50; font-size: 18px; font-weight: bold;'>Paul Kaldi Management Team</p>
                    <p style='margin: 5px 0 0 0; color: #666; font-size: 14px;'>TrackIT Employee Management System</p>
                </div>
            </div>
            
            <div style='text-align: center; padding: 20px; color: #666; font-size: 12px;'>
                <p>This is an automated message from the Paul Kaldi TrackIT system.</p>
                <p>Please do not reply to this email.</p>
            </div>  
        </div>
    </body>
    </html>";
}
?>