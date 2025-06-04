<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Documentation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="trackit-global.css">
    <link rel="stylesheet" href="trackit-doc.css">
    <link rel="author" href="humans.txt">
  </head>
  <body>
    <div class="doc__bg"></div>
    <nav class="header">
      <h1 class="logo">TrackIt <span class="logo__thin">Documentation</span></h1>
      <ul class="menu">
        <div class="menu__item toggle"><span></span></div>
        <li class="menu__item"><a href="docu.php" class="link link--dark"><i class="fa fa-back"></i> Back</a></li>
      </ul>
    </nav>
    <div class="wrapper">
      <aside class="doc__nav">
        <ul>
          <li class="js-btn selected">Getting Started</li>
          <li class="js-btn">User Roles & Permissions</li>
          <li class="js-btn">Notifications</li>
          <li class="js-btn">Account Settings</li>
          <li class="js-btn">FAQs</li>
          <li class="js-btn">Support</li>
        </ul>
      </aside>
      <article class="doc__content">
        <section class="js-section">
          <h3 class="section__title">Get Started</h3>
          <p>Users can register or log in, create workspaces, and begin managing tasks for personal or team projects.</p>
          <p>For Managers: To create a task, navigate to your chosen workspace and click on “New Task.” You’ll be prompted to enter a task title and description, set a deadline, assign team members, and select a priority level. Once saved, tasks can be updated and marked as “Complete,” “In Progress,” or “Pending” depending on their status.</p>
          <p>For Staffs: Assigned tasks will reflect on your dashboard. Although, you cannot add your own work and set deadlines, we have another feature wherein you can list personal reminders for events and allow TrackIt to notify your calendar.</p>
        </section>

        <section class="js-section">
          <h3 class="section__title">User Roles & Permissions</h3>
          <p>There are three user roles: Admin/Manager (full access to tasks and staff management), and staff (limited to assigned tasks and personal reminders).</p>
          <table id="customers">
            <tr>
              <th>Role</th>
              <th>Description</th>
              <th>Permissions</th>
            </tr>
            <tr>
              <td>Admin</td>
              <td>System owner or main controller</td>
              <td>Full access to all workspaces, users, tasks, settings, and activity logs</td>
            </tr>
            <tr>
              <td>Manager</td>
              <td>Team/project lead or coordinator</td>
              <td>Can create/edit tasks, assign members, manage workspaces, and set own schedules</td>
            </tr>
            <tr>
              <td>Staff</td>
              <td>Regular user or contributor</td>
              <td>Can view, update, and delete on tasks assigned to them and set personal reminders</td>
            </tr>
          </table>
          <hr />
        </section>
        <section class="js-section">
          <h3 class="section__title">Notifications</h3>
          <p>TrackIT keeps you updated through email and in-app notifications. You’ll receive alerts for new task assignments, upcoming deadlines, and comments made on tasks. Users can also opt into daily summary emails, which can be enabled or disabled in their account settings.</p>
          <hr />
        </section>
        <section class="js-section">
          <h3 class="section__title">Account Settings</h3>
          <p>Under the Account Settings, users can change their passwords, update their email addresses, and set notification preferences. Future updates will include the ability to connect third-party tools and calendar integrations.</p>
        </section>
        <section class="js-section">
          <h3 class="section__title">FAQs</h3>
          <p><b>Q: Who can delete tasks in TrackIT?</b></p>
          <p>A: Only Admins and Managers have permission to delete tasks. Deleted tasks are archived for 30 days before being permanently removed.</p>

          <p><b>Q: What happens to task data after a few months?</b></p>
          <p>A: Task data and activity logs are retained for three months. At the start of the fourth month, data from the first month will be automatically deleted. A PDF summary of that month’s data will be generated and sent to Admins and Managers before deletion.</p>

          <p><b>Q: Is TrackIT free to use?</b></p>
          <p>A:  Yes, TrackIT offers a free tier with essential features.</p>

          <p><b>Q: How do notifications work?</b></p>
          <p>A: Users receive real-time email alerts for task updates, assignments, comments, and deadlines.</p>

          <hr/>
        </section>
        <section class="js-section">
          <h3 class="section__title">Support</h3>
          <p>For any assistance or inquiries, feel free to reach out to our support team at support@trackit.web or visit our support page for more help.</p>
        </section>
      </article>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
    <script src="trackit-docu.js"></script>
  </body>
</html>