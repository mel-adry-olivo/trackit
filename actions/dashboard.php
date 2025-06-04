

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css?v=<?php echo time(); ?>" type="text/css" />
</head>
<body>
<div class="dash-container">
    <nav>
        <div class="dash-nav-buttons">
            <a href="#" id="home" class="active">Home</a>
            <a href="#" id="account">Account</a>
        </div>
        <div class="settings">
            <span>Settings</span>
            <div class="dropdown">
                <a href="#">Profile</a>
                <a href="#">Preferences</a>
                <a href="../logout.php">Log Out</a>
            </div>
        </div>
    </nav>
    
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>

    <div class="dash-tabs">
        <a href="#" class="nav-item"><span>Main Table</span></a>
        <a href="#" class="nav-item"> <span>Gantt</span></a>
        <a href="#" class="nav-item"> <span>Dashboard</span></a>
    </div>

    <div class="dash-section">
        <h2 class="active-work">Active work</h2>

        <!-- Form to Add New Task -->
        <form action="add_task.php" method="POST">
            <table class="dash-status">
                <tr>
                    <td><input type="text" name="task" placeholder="Enter task" class="task-input" required></td>
                    <td><input type="text" name="timeline" placeholder="Enter timeline" class="task-input" required></td>
                    <td>
                        <select name="status" class="task-select">
                            <option value="done">Done</option>
                            <option value="working">Working on it</option>
                            <option value="stuck">Stuck</option>
                        </select>
                    </td>
                    <td><input type="date" name="date" class="task-input" required></td>
                    <td>
                        <select name="priority" class="task-select">
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </td>
                    <td><button type="submit" class="save-btn">Add Task</button></td>
                </tr>
            </table>
        </form>

        <!-- Table to Display Tasks -->
        <table class="dash-status">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Timeline</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Priority</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['task']); ?></td>
                    <td><?php echo htmlspecialchars($row['timeline']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['priority']); ?></td>
                    <td>
                    <form action="delete_task.php" method="POST">
                         <input type="hidden" name="id" value="<?= $row['id'] ?>">
                         <button type="submit" name="delete" class="delete-btn">Delete</button>
                </form>

                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

