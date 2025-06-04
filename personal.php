<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal</title>
    <link rel="stylesheet" href="./assets/css/styles.css">

</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo"><a href="index.php" class="home">TrackIT</a></div>
            <div class="nav-buttons">
                <a href="personal.php" id="employee" class="active">Personal</a>
                <a href="business.php" id="manager">Business</a>
            </div>
        </nav>
    </header>

    <main>
        <section class="employee-hero">
            <div class="employee-hero-text">
                <h1>Stay Organized & Focused on What Matters</h1>
                <p><b>Tired of confusing schedules and last-minute shift changes?<br>We have the right answer to your
                        problem!</b><br><br>TrackIT keeps everything organized—check your shifts, track tips, manage
                    tasks, and stay in the loop with real-time updates. Clock in easily, access training, and focus on
                    what you do best—serving great coffee! Let TrackIT handle the details while you crush your shift.
                </p>
                <a href="login.php"><button class="employee-cta">Login Here</button></a>
            </div>
        </section>


        <section class="employee-benefits">
            <div class="employee-stat-card-one">
                <h3>+40% Faster Order Processing</h3><br><br><br>
                <p>Reduce wait times with easy order management.</p>
            </div>

            <div class="employee-stat-card-two">
                <h3>+25% Increase in Task Efficiency</h3><br><br><br>
                <p>Stay on top of daily tasks with automated reminders.</p>
            </div>

            <div class="employee-stat-card-three">
                <h3>80% Less Miscommunication</h3><br><br><br>
                <p>Ensures fewer mistakes and smoother operations.</p>
            </div>
        </section>
    </main>

    <!--FOOTER-->
    <footer class="footer-bg-personal">
        <div class="footer-container-personal">
            <div class="footer-top-personal">
                <h2>TRACKIT®</h2>
            </div>

            <div class="footer-bottom-personal">
                <div class="footer-column-personal">
                    <h3>NAVIGATION</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="personal.php">Personal</a></li>
                        <li><a href="business.php">Business</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>COMPANY</h3>
                    <ul>
                        <li><a href="company.php">About Us</a></li>
                        <li><a href="company.php">Team</a></li>
                        <li><button class="contact-btn">CONTACT US</button></li>
                    </ul>
                </div>
                <div class="footer-column-personal">
                    <h3>SUPPORT</h3>
                    <ul>
                        <li><a href="docu.php">Documentation</a></li>
                        <li><a href="#">Help Center</a></li>
                        <li><button class="support-btn">ASK FOR SUPPORT</button></li>
                    </ul>
                </div>
            </div>

        </div>
    </footer>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>