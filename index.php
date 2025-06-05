<?php

session_start();
// include "database/database.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="assets/css/styles.css" type="text/css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous" defer></script> -->
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo"><a href="./index.php" class="home">TrackIT</a></div>
            <div class="nav-buttons">
                <a href="personal.php" id="employee" class="active">Personal</a>
                <a href="business.php" id="manager">Business</a>
            </div>
        </nav>
    </header>
    <div class="greetings animate-on-scroll">
        <h1>Less chaos, <br><span class="coffee">more coffee.</span></h1>
        <p>Welcome to <b>Paul Kaldi's</b> TrackIT – Simplify your workflow, amplify your productivity.</p>
        <div class="buttons">
            <a href="login.php" class="login">Log in</a>
            <a href="#info-page" class="learn-more">Learn More</a>
        </div>
    </div>
    <div class="partners">
        <p>In partnership with<br></p>
        <div class="partners-logos">
            <div class="partner-track">
                <div class="partner-item">
                    <img src="assets/img/pk-gray.png" alt="Paul Kaldi" class="partner-logo">
                    <div class="logo-name">
                        <p>· PAUL KALDI'S LOCALLY SOURCED COFFEE BEANS ·</p>
                    </div>
                </div>
                <div class="partner-item">
                    <img src="assets/img/fs-gray.png" alt="Foreal Solutions" class="partner-logo">
                    <div class="logo-name">
                        <p>· FOREAL SOLUTIONS ·</p>
                    </div>
                </div>
                <div class="partner-item">
                    <img src="assets/img/trackit-gray.png" alt="TrackIt" class="partner-logo">
                    <div class="logo-name">
                        <p>· BY GUANCIA | POLARON | SIATON | TABIOLO ·</p>
                    </div>
                </div>

                <div class="partner-item">
                    <img src="assets/img/pk-gray.png" alt="Paul Kaldi" class="partner-logo">
                    <div class="logo-name">
                        <p>· PAUL KALDI'S LOCALLY SOURCED COFFEE BEANS ·</p>
                    </div>
                </div>
                <div class="partner-item">
                    <img src="assets/img/fs-gray.png" alt="Foreal Solutions" class="partner-logo">
                    <div class="logo-name">
                        <p>· FOREAL SOLUTIONS ·</p>
                    </div>
                </div>
                <div class="partner-item">
                    <img src="assets/img/trackit-gray.png" alt="TrackIt" class="partner-logo">
                    <div class="logo-name">
                        <p>· BY GUANCIA | POLARON | SIATON | TABIOLO ·</p>
                    </div>
                </div>

                <div class="partner-item">
                    <img src="assets/img/pk-gray.png" alt="Paul Kaldi" class="partner-logo">
                    <div class="logo-name">
                        <p>· PAUL KALDI'S LOCALLY SOURCED COFFEE BEANS ·</p>
                    </div>
                </div>
                <div class="partner-item">
                    <img src="assets/img/fs-gray.png" alt="Foreal Solutions" class="partner-logo">
                    <div class="logo-name">
                        <p>· FOREAL SOLUTIONS ·</p>
                    </div>
                </div>
                <div class="partner-item">
                    <img src="assets/img/trackit-gray.png" alt="TrackIt" class="partner-logo">
                    <div class="logo-name">
                        <p>· BY GUANCIA | POLARON | SIATON | TABIOLO ·</p>
                    </div>
                </div>

                <div class="partner-item">
                    <img src="assets/img/pk-gray.png" alt="Paul Kaldi" class="partner-logo">
                    <div class="logo-name">
                        <p>· PAUL KALDI'S LOCALLY SOURCED COFFEE BEANS ·</p>
                    </div>
                </div>
                <div class="partner-item">
                    <img src="assets/img/fs-gray.png" alt="Foreal Solutions" class="partner-logo">
                    <div class="logo-name">
                        <p>· FOREAL SOLUTIONS ·</p>
                    </div>
                </div>
                <div class="partner-item" id="info-page">
                    <img src="assets/img/trackit-gray.png" alt="TrackIt" class="partner-logo">
                    <div class="logo-name">
                        <p>· BY GUANCIA | POLARON | SIATON | TABIOLO ·</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="carousel-container animate-on-scroll">
        <div class="carousel-track">
            <div class="carousel-slide" style="background-image: url('./assets/img/PHOTO4.jpg');">
                <div class="carousel-slide-content">
                    <h4>Instant Updates | Alert Notifications</h4>
                    <h3>Real-Time Alerts & Reminders</h3>
                    <p>Sends instant notifications to keep everyone on track and accountable, reducing bottlenecks in
                        service.</p>
                </div>
            </div>
            <div class="carousel-slide" style="background-image: url('./assets/img/PHOTO1.jpg');">
                <div class="carousel-slide-content">
                    <h4>Task Assignments | Baristas & Staffs</h4>
                    <h3>Improve Team Collaboration</h3>
                    <p>Assigning and tracking tasks (e.g., brewing, shift duties) reduces confusion and increases
                        efficiency.</p>
                </div>
            </div>
            <div class="carousel-slide" style="background-image: url('./assets/img/PHOTO2.jpg');">
                <div class="carousel-slide-content">
                    <h4>Efficiency Boost | Managers & Staffs</h4>
                    <h3>Saves Both Time and Effort</h3>
                    <p>Automated task assignments—freeing up time for managers to focus on customer experience.</p>
                </div>
            </div>
            <div class="carousel-slide" style="background-image: url('./assets/img/PHOTO3.jpg');">
                <div class="carousel-slide-content">
                    <h4>Daily Operations | All Staff Levels</h4>
                    <h3>Smooth Operation and Management</h3>
                    <p>Schedules tasks to ensure all duties, like prep, inventory, and closing are completed without a
                        miss.</p>
                </div>
            </div>
        </div>
        <button class="carousel-btn prev">&#10094;</button>
        <button class="carousel-btn next">&#10095;</button>
        <a href="#info-see" class="arrow-btn" aria-label="Scroll down">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                stroke="#c6ff41" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="11" fill="none" />
                <polyline points="8 12 12 16 16 12" />
            </svg>
        </a>
    </div>
    <section class="info-see-section">
        <header id="info-see" class="info-see-header animate-on-scroll">
            <h1>See how the system works!</h1>
            <nav class="info-see-nav">
                <span>PRIORITIZE<br />YOUR TASKS</span>
                <span>TRACK<br />PROGRESS</span>
                <span>COLLABORATE<br />WITH TEAM</span>
            </nav>
        </header>
        <main class="info-see-hero">
            <div class="info-see-bow-container">

                <video id="demoVideo" controls autoplay muted playsinline class="info-see-bg-video">
                    <source src="assets/video/demo.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div class="info-see-widget info-see-widget-top digital-clock">
                    <div class="info-see-clock-label">Current Time</div>
                    <div id="currentTime" class="info-see-clock-display">--:--</div>
                </div>
                <div class="info-see-location-widget ">
                    <div class="info-see-location-map"
                        style="border-radius: 10px; overflow: hidden; margin-bottom: 1rem;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3930.4126831848127!2d122.56287721428087!3d10.71698279238108!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33aee4b4a93123c3%3A0xf8c15a4c51ec3e71!2sBINHI%20-%20Technological%20Business%20Incubator%20Center%20(WVSU)!5e0!3m2!1sen!2sph!4v1715927264642!5m2!1sen!2sph"
                            width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="info-see-location-info">
                        <div class="info-see-location-label">Current Location</div>
                        <div class="info-see-location-address">
                            <strong>WVSU-BINHI TBI</strong><br />
                            📍 West Visayas State University, <br>Luna St, La Paz, Iloilo City, Philippines
                        </div>
                        <button class="info-see-location-share"
                            onclick="window.open('https://www.google.com/maps?q=WVSU+BINHI+TBI,+West+Visayas+State+University,+La+Paz,+Iloilo+City', '_blank')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="#000">
                                <path
                                    d="M13 5.828V16a1 1 0 0 1-2 0V5.828L7.414 9.414a1 1 0 0 1-1.414-1.414l6-6a1 1 0 0 1 1.414 0l6 6a1 1 0 0 1-1.414 1.414L13 5.828z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- <div class="info-see-waveform ">
                    <div class="info-see-bar"></div>
                    <div class="info-see-bar"></div>
                    <div class="info-see-bar"></div>
                    <div class="info-see-bar"></div>
                </div> -->

        </main>
    </section>
    <div id="contactModal" class="modal-overlay" style="display:none;">
        <div class="modal-box">
            <button class="modal-close">&times;</button>
            <p><strong>For questions and inquiries about the system, please call or message:</strong><br>
                Meryll Klaryze Polaron || 09763669351</p><br>
            <p><strong>Or send us an email at:</strong><br>
                contact@trackit.web</p><br>
        </div>
    </div>
    <div id="supportModal" class="modal-overlay" style="display:none;">
        <div class="modal-box">
            <button class="modal-close">&times;</button>
            <p><strong>For support please message:</strong><br>support@trackit.web</p>
            <textarea id="supportMessage" placeholder="Describe your problem here..." rows="5"
                style="width: 100%; margin: 10px 0; padding: 8px; border: 1px solid #ccc; border-radius: 5px;"></textarea>
            <button id="submitSupport"
                style="padding: 10px 15px; background-color: #00252e; color: #fff; border: none; border-radius: 5px; cursor: pointer;">Submit</button>
        </div>
    </div>
    <footer class="footer-bg ">
        <div class="footer-container">
            <div class="footer-top">
                <h2>TRACKIT®</h2>
            </div>

            <div class="footer-bottom">
                <div class="footer-column">
                    <h3>NAVIGATION</h3>
                    <ul>
                        <li><a href="./index.php">Home</a></li> <!-- previously href="#landing" -->
                        <li><a href="./login.php">Login</a></li>
                        <li><a href="./personal.php">Personal</a></li>
                        <li><a href="./business.php">Business</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>COMPANY</h3>
                    <ul>
                        <li><a href="./company.php">About Us</a></li>
                        <li><a href="./about\team.php">Team</a></li>
                        <li><button class="contact-btn">CONTACT US</button></li>
                    </ul>
                </div>


                <div class="footer-column">
                    <h3>SUPPORT</h3>
                    <ul>
                        <li><a href="./docu.php">Documentation</a></li>
                        <br>
                        <li><button class="support-btn">ASK FOR SUPPORT</button></li>
                    </ul>
                </div>
            </div>

            <div class="footer-legal">
                <p>© 2025 Foreal Solutions</p>
                <ul>
                    <li><a href="./privacy.php">Terms of Service</a></li>
                    <li><a href="./privacy.php">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
    </footer>
    <script src="./assets/js/main/home.js" defer></script>
</body>

</html>