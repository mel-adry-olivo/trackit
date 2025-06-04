<?php
function isActive($page)
{
  return basename($_SERVER['SCRIPT_NAME']) === $page ? 'emp-active' : '';
}
?>

<div class="sidebar">
  <div class="header">
    <h1>TrackIt</h1>
  </div>
  <ul class="emp-sidebar">
    <li class="emp-item <?= isActive('emp-db.php') ?>">
      <a href="emp-db.php">
        <img class="nav-icon" src="assets/svg/dashboard.svg" alt="Dashboard Icon" width="16" height="16">Dashboard
      </a>
    </li>
    <li class="emp-item <?= isActive('emp_works.php') ?>">
      <a href="emp_works.php">
        <img class="nav-icon" src="assets/svg/notes.svg" alt="My Works Icon" width="16" height="16">My Works
      </a>
    </li>
    <li class="emp-item <?= isActive('emp_reminders.php') ?>">
      <a href="emp_reminders.php">
        <img class="nav-icon" src="assets/svg/reminders.svg" alt="Reminders Icon" width="16" height="16">Reminders
      </a>
    </li>
    <li class="emp-item <?= isActive('emp_acc.php') ?>">
      <a href="emp_acc.php">
        <img class="nav-icon" src="assets/svg/acc.svg" alt="Account Icon" width="16" height="16">Account
      </a>
    </li>
    <li class="emp-item <?= isActive('emp_settings.php') ?>">
      <a href="emp_settings.php">
        <img class="nav-icon" src="assets/svg/settings.svg" alt="Account Icon" width="16" height="16">Settings
      </a>
    </li>
    <!-- <li class="emp-item">
      <a href="./logout.php">
        <img class="nav-icon" src="assets/svg/logout.svg" alt="Logout Icon" width="16" height="16">Logout
      </a>
    </li> -->
  </ul>
</div>