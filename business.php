<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business</title>
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
                <h1>Manage Your Coffee Shop with Ease & Comfort</h1>
                <p><b>Juggling schedules and keeping your team together<br>can be overwhelming—but it doesn’t have to
                        be. </b> <br><br>With TrackIT, you can effortlessly assign shifts, monitor performance, and
                    manage tasks all in one place. Get real-time updates, automate tracking, and focus on growing your
                    business. Keep your team efficient and your coffee shop running smoothly with TrackIT!</p>
                <a href="login.php"><button class="employee-cta">Login Here</button></a>
            </div>
        </section>


        <section class="employee-benefits">
            <div class="employee-stat-card-one">
                <h3>+50% Faster Staff Scheduling</h3><br><br>
                <p>Automated scheduling reduces conflicts and keeps shifts organized.</p>
            </div>

            <div class="employee-stat-card-two">
                <h3>-70% Less Paperwork</h3><br><br>
                <p>Digital tracking for schedules, orders, and reports saves time.</p>
            </div>

            <div class="employee-stat-card-three">
                <h3>+45% Faster Training Process</h3><br><br>
                <p>New employees onboard easily with built-in training modules.</p>
            </div>
        </section>
    </main>

    <!--FOOTER-->
    <footer class="footer-bg-personal">
        <div class="footer-container">
            <div class="footer-top">
                <h2>TRACKIT®</h2>
            </div>

            <div class="footer-bottom">
                <div class="footer-column">
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
                <div class="footer-column">
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