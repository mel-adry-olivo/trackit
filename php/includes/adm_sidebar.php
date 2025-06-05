<?php

function isActive($page)
{
  return basename($_SERVER['SCRIPT_NAME']) == $page ? 'mgr-active' : '';
}
?>

<aside class="mgr-sidebar">
  <h2>TrackIT</h2>
  <ul>
    <li class="<?php echo isActive('index.php'); ?>">
      <a href="../admin/index.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/dashboard.svg" alt="Dashboard Icon" width="16" height="16">
        Dashboard
      </a>
    </li>
    <li class="<?php echo isActive(page: 'admin-hire.php'); ?>">
      <a href="../admin/admin_hire.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/hire.svg" alt="Hire Icon" width="16" height="16">
        Register Manager
      </a>
    </li>
    <li class="<?php echo isActive('admin_all_tasks.php'); ?>">
      <a href="../admin/admin_all_tasks.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/all-tasks.svg" alt="Tasks Icon" width="16" height="16">
        All Tasks
      </a>
    </li>
    <li class="<?php echo isActive('admin_assign_.php'); ?>">
      <a href="../admin/admin_assign_.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/assign-task.svg" alt="Assign Icon" width="16" height="16">
        Assign Tasks
      </a>
    </li>
    <li class="<?php echo isActive('admin_reminder.php'); ?>">
      <a href="../admin/admin_reminder.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/reminders.svg" alt="Reminder Icon" width="16" height="16">
        Reminders
      </a>
    </li>
    <li class="<?php echo isActive('admin_thistory.php'); ?>">
      <a href="../admin/admin_thistory.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/task-history.svg" alt="Task History Icon" width="16" height="16">
        Task History
      </a>
    </li>
    <li class="<?php echo isActive('admin_comp.php'); ?>">
      <a href="../admin/admin_comp.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/company.svg" alt="Company Icon" width="16" height="16">
        Company
      </a>
    </li>
    <li class="<?php echo isActive('admin_rank.php'); ?>">
      <a href="../admin/admin_rank.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/rank.svg" alt="Rankings Icon" width="16" height="16">
        Rankings
      </a>
    </li>
    <li class="<?php echo isActive('admin_acc.php'); ?>">
      <a href="../admin/admin_acc.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/acc.svg" alt="Account Icon" width="16" height="16">
        Account
      </a>
    </li>
    <li class="<?php echo isActive('admin_settings.php'); ?>">
      <a href="../admin/admin_settings.php" class="nav-link">
        <img class="nav-icon" src="assets/svg/settings.svg" alt="Settings Icon" width="16" height="16">
        Settings
      </a>
    </li>
  </ul>
</aside>