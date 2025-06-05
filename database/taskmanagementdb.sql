-- Drop tables if they exist
DROP TABLE IF EXISTS `tasks`;
DROP TABLE IF EXISTS `task_templates`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `notes`;
DROP TABLE IF EXISTS `behavioral_evaluations`;

CREATE TABLE `users` (
  `uid` INT AUTO_INCREMENT PRIMARY KEY,

  -- Basic Account Info
  `username` VARCHAR(75) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,

  -- Personal Information
  `firstname` VARCHAR(100) NOT NULL,
  `lastname` VARCHAR(100) NOT NULL,
  `full_name` VARCHAR(200) GENERATED ALWAYS AS (CONCAT(`firstname`, ' ', `lastname`)) STORED,
  `date_of_birth` DATE DEFAULT NULL,
  `place_of_birth` VARCHAR(150) DEFAULT NULL,
  `gender` ENUM('Male', 'Female', 'Non-binary', 'Other') DEFAULT NULL,
  `civil_status` ENUM('Single', 'Married', 'Widowed', 'Separated', 'Divorced') DEFAULT NULL,
  `nationality` VARCHAR(100) DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `address` TEXT DEFAULT NULL,

  -- Employment Info
  `start_date` DATE DEFAULT NULL,
  `end_date` DATE DEFAULT NULL,
  `role` ENUM('employee', 'manager') NOT NULL DEFAULT 'employee',
  `created_at` DATE DEFAULT CURRENT_DATE,
  `job_title` VARCHAR(100) DEFAULT NULL,

  -- Emergency Contact
  `emergency_contact_name` VARCHAR(150) DEFAULT NULL,
  `emergency_relationship` VARCHAR(100) DEFAULT NULL,
  `emergency_phone` VARCHAR(20) DEFAULT NULL,

  -- Profile
  `profile_image` VARCHAR(255) DEFAULT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Create Task Templates Table
CREATE TABLE `task_templates` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `description` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create Tasks Table (updated)
CREATE TABLE `tasks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `assigned_by` INT NOT NULL,
  `assigned_to` INT NOT NULL,
  `template_id` INT NOT NULL,
  `timeline` VARCHAR(255) NOT NULL,
  `status` ENUM('Not Started', 'In Progress', 'Done', 'Overdue') NOT NULL DEFAULT 'Not Started',
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `priority` ENUM('High', 'Medium', 'Low') NOT NULL DEFAULT 'medium',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`assigned_by`) REFERENCES `users`(`uid`) ON DELETE CASCADE,
  FOREIGN KEY (`assigned_to`) REFERENCES `users`(`uid`) ON DELETE CASCADE,
  FOREIGN KEY (`template_id`) REFERENCES `task_templates`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    alarm_time DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `behavioral_evaluations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `employee_id` INT NOT NULL,
  `evaluated_by` INT NOT NULL,
  `week_start_date` DATE NOT NULL,
  `week_end_date` DATE NOT NULL,
  `scores` JSON NOT NULL, -- Stores all 10 Q scores like {"Q1":5,"Q2":3,...}
  `total_score` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`employee_id`) REFERENCES `users`(`uid`) ON DELETE CASCADE,
  FOREIGN KEY (`evaluated_by`) REFERENCES `users`(`uid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



-- ===========================================================

INSERT INTO `users`
(
  `username`, `firstname`, `lastname`, `email`, `password`, `role`,
  `date_of_birth`, `place_of_birth`, `gender`, `civil_status`, `nationality`, `phone`, `address`,
  `start_date`, `end_date`, `created_at`, `job_title`,
  `emergency_contact_name`, `emergency_relationship`, `emergency_phone`, `profile_image`
) VALUES
(
  'shi', 'Shenna Jane', 'Guancia', 'shennajane@gmail.com', '$2y$10$VNxo8oMjzY9EO.kQWwF/xO2aC0/xlKrVjkyM07WwZl1H2JGgdzmGi', 'manager',
  '1988-05-14', 'New York', 'Female', 'Married', 'American', '555-1234', '123 Main St, New York, NY',
  '2020-01-01', NULL, '2020-01-01', 'Sales Manager',
  'Robert Guancia', 'Husband', '555-5678', NULL
),
(
  'mk00000017', 'Meryll Klaryze', 'Polaron', 'meryllklaryze.polaron@gmail.com', '$2y$10$gLTNSgR89kRpAvrNDeYkouietoDrxpvJalrQWuW/eRLcJrn8QTAcK', 'manager',
  '1990-09-30', 'Los Angeles', 'Female', 'Single', 'Filipino', '555-4321', '456 Elm St, Los Angeles, CA',
  '2021-06-15', NULL, '2021-06-15', 'Marketing Director',
  'Jose Polaron', 'Father', '555-8765', NULL
),
(
  'john123', 'John', 'Doe', 'john.doe@email.com', '$2y$10$4/LP1mEvSqdL8SgCxabE0uBns08vjvB/ZVXnaYElifI2vp.d1jwYS', 'employee',
  '1995-02-20', 'Chicago', 'Male', 'Single', 'American', '555-7890', '789 Pine St, Chicago, IL',
  '2023-03-01', NULL, '2023-03-01', 'Support Specialist',
  'Jane Doe', 'Sister', '555-3456', NULL
),
(
  'amy.santiago', 'Amy', 'Santiago', 'amy.santiago@example.com', '$2y$10$5cAmyHashedPassHere', 'employee',
  '1991-03-12', 'Brooklyn', 'Female', 'Married', 'American', '555-1111', '321 4th St, Brooklyn, NY',
  '2024-02-15', NULL, '2024-02-15', 'Barista',
  'Jake Peralta', 'Husband', '555-2222', NULL
),
(
  'terry.j', 'Terry', 'Jeffords', 'terry.jeffords@example.com', '$2y$10$5cTerryHashedPassHere', 'employee',
  '1980-06-30', 'New York', 'Male', 'Married', 'American', '555-3333', '654 5th Ave, New York, NY',
  '2023-11-01', NULL, '2023-11-01', 'Inventory Coordinator',
  'Sharon Jeffords', 'Wife', '555-4444', NULL
),
(
  'gina.linetti', 'Gina', 'Linetti', 'gina.linetti@example.com', '$2y$10$5cGinaHashedPassHere', 'employee',
  '1985-04-17', 'Queens', 'Female', 'Single', 'American', '555-5555', '987 6th Ave, Queens, NY',
  '2025-01-05', NULL, '2025-01-05', 'Customer Relations',
  'Darlene Linetti', 'Mother', '555-6666', NULL
),
(
  'rosa.diaz', 'Rosa', 'Diaz', 'rosa.diaz@example.com', '$2y$10$5cRosaHashedPassHere', 'employee',
  '1988-09-22', 'Bronx', 'Female', 'Single', 'American', '555-7777', '432 7th Ave, Bronx, NY',
  '2024-06-20', NULL, '2024-06-20', 'Maintenance Lead',
  'Unknown', 'Friend', '555-8888', NULL
),
(
  'charles.boyle', 'Charles', 'Boyle', 'charles.boyle@example.com', '$2y$10$5cBoyleHashedPassHere', 'employee',
  '1982-11-08', 'Brooklyn', 'Male', 'Divorced', 'American', '555-9999', '213 8th Ave, Brooklyn, NY',
  '2023-08-10', NULL, '2023-08-10', 'Kitchen Supervisor',
  'Genevieve Mirren-Carter', 'Partner', '555-0001', NULL
);



-- ===========================================================

INSERT INTO `task_templates` (`description`) VALUES
('Full Inventory Audit (Count all stock, check expiry dates)'),
('Reorganize Storage Room (Label shelves, FIFO system)'),
('Compare Supplier Prices & Negotiate Discounts'),
('Place Monthly Bulk Order (Coffee, cups, syrups)'),
('Deep Clean & Descale Espresso Machine (Full disassembly)'),
('Service Coffee Grinders (Replace burrs, calibrate)'),
('Clean Refrigerator Coils & Check Seals'),
('Test & Introduce 1 New Seasonal Drink'),
('Update Menu Board/Printed Menus'),
('Conduct Staff Coffee Tasting (Train on new beans)'),
('Deep Clean Floor Grout & Drains'),
('Repaint Chipped Walls/Furniture'),
('Reorganize Café Layout for Efficiency'),
('Create/Update Training Manual (Recipes, POS steps)'),
('Analyze 1 Month of Sales Data (Identify waste/low sellers)'),
('Plan a Staff Appreciation Day (Budget, activities)');

INSERT INTO `tasks` (`assigned_by`, `assigned_to`, `template_id`, `timeline`, `status`, `start_date`, `end_date`, `priority`) VALUES
(1, 3, 5, '2 days', 'In Progress', '2025-03-18', '2025-03-20', 'High'),
(2, 3, 14, '3 days', 'Not Started', '2025-04-10', '2025-04-12', 'Medium');

-- Completed Tasks (status: Done, end_date in past)
INSERT INTO `tasks` (`assigned_by`, `assigned_to`, `template_id`, `timeline`, `status`, `start_date`, `end_date`, `priority`) VALUES
(1, 3, 1, '3 days', 'Done', '2025-02-01', '2025-02-03', 'High'),
(2, 3, 2, '1 day', 'Done', '2025-01-15', '2025-01-15', 'Low');

-- Overdue Tasks (status: Overdue, end_date in past)
INSERT INTO `tasks` (`assigned_by`, `assigned_to`, `template_id`, `timeline`, `status`, `start_date`, `end_date`, `priority`) VALUES
(1, 3, 3, '2 days', 'Overdue', '2025-05-01', '2025-05-03', 'Medium'),
(2, 3, 4, '1 week', 'Overdue', '2025-04-20', '2025-04-27', 'High');

-- Active In Progress (status: In Progress, end_date in future)
INSERT INTO `tasks` (`assigned_by`, `assigned_to`, `template_id`, `timeline`, `status`, `start_date`, `end_date`, `priority`) VALUES
(1, 3, 6, '4 days', 'In Progress', '2025-06-01', '2025-06-04', 'Medium'),
(2, 3, 7, '2 days', 'In Progress', '2025-06-03', '2025-06-05', 'Low');

-- Upcoming Tasks (status: Not Started, future dates)
INSERT INTO `tasks` (`assigned_by`, `assigned_to`, `template_id`, `timeline`, `status`, `start_date`, `end_date`, `priority`) VALUES
(1, 3, 8, '2 days', 'Not Started', '2025-06-10', '2025-06-11', 'High'),
(2, 3, 9, '1 day', 'Not Started', '2025-06-15', '2025-06-15', 'Low');
