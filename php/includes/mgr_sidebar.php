<?php

function isActive($page)
{
  return basename($_SERVER['SCRIPT_NAME']) == $page ? 'mgr-active' : '';
}
?>

<aside class="mgr-sidebar">
  <h2>TrackIT</h2>
  <ul>
    <li class="<?php echo isActive('manager_home.php'); ?>">
      <a href="manager_home.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/dashboard.svg" alt="Dashboard Icon" width="16" height="16">
        Dashboard
      </a>
    </li>
    <li class="<?php echo isActive('mgr_hire_emp.php'); ?>">
      <a href="mgr_hire_emp.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/hire.svg" alt="Hire Icon" width="16" height="16">
        Register Employee
      </a>
    </li>
    <li class="<?php echo isActive('mgr_all_tasks.php'); ?>">
      <a href="mgr_all_tasks.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/all-tasks.svg" alt="Tasks Icon" width="16" height="16">
        All Tasks
      </a>
    </li>
    <li class="<?php echo isActive('mgr_assign_.php'); ?>">
      <a href="mgr_assign_.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/assign-task.svg" alt="Assign Icon" width="16" height="16">
        Assign Tasks
      </a>
    </li>
    <li class="<?php echo isActive('mgr_reminder.php'); ?>">
      <a href="mgr_reminder.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/reminders.svg" alt="Reminder Icon" width="16" height="16">
        Reminders
      </a>
    </li>
    <li class="<?php echo isActive('mgr_thistory.php'); ?>">
      <a href="mgr_thistory.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/task-history.svg" alt="Task History Icon" width="16" height="16">
        Task History
      </a>
    </li>
    <li class="<?php echo isActive('mgr_comp.php'); ?>">
      <a href="mgr_comp.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/company.svg" alt="Company Icon" width="16" height="16">
        Company
      </a>
    </li>
    <li class="<?php echo isActive('mgr_rank.php'); ?>">
      <a href="mgr_rank.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/rank.svg" alt="Rankings Icon" width="16" height="16">
        Rankings
      </a>
    </li>
    <li class="<?php echo isActive('mgr_tracker.php'); ?>">
      <a href="mgr_tracker.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/tracker.svg" alt="Behavior Tracker Icon" width="16" height="16">
        Behavior Tracker
      </a>
    </li>
    <li class="<?php echo isActive('mgr_acc.php'); ?>">
      <a href="mgr_acc.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/acc.svg" alt="Account Icon" width="16" height="16">
        Account
      </a>
    </li>
    <li class="<?php echo isActive('mgr_settings.php'); ?>">
      <a href="mgr_settings.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/settings.svg" alt="Settings Icon" width="16" height="16">
        Settings
      </a>
    </li>
    <!-- <li>
      <a href="./logout.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/logout.svg" alt="Logout Icon" width="16" height="16">
        Logout
      </a>
    </li> -->
  </ul>
</aside>