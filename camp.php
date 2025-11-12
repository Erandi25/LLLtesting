<?php
include 'includes/db.php'; // Make sure path is correct
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Camp Details</title>
    <link rel="stylesheet" href="camp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .image-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .image-gallery img {
            max-width: 300px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <header>
        <div class="navbar">dddd
            <nav>
                <ul>
                    <li><a href="hospital.html">HOSPITAL</a></li>
                    <li><a href="camp.php">CAMP</a></li>
                    <li><a href="Aboutusnew.html">ABOUT</a></li>
                    <li><a href="Contactus.html">CONTACT US</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="logo">
                <a href="Home.php">
                    <img src="LOGO NEW.png" alt="Life Line Locator Logo">
                </a>
            </div>
            <div class="hero-text">
                <h1>LIFE LINE LOCATOR</h1>
                <h3>Your trusted platform for blood and organ donation.</h3>
                <a href="Aboutusnew.html" class="btn">Learn More</a>
            </div>
        </section>

        <br><br>  
        <header class="header">
            <div class="container">
                <h1>Organize a Camp</h1>
                <p><strong>Life Line Locator's camp management solution helps you organize a voluntary<br>
                    blood donation camp from one single platform.</strong></p>
            </div>
        </header>

        <br><br>

        <!-- Dynamic Image Gallery -->
        <div class="image-gallery">
            <?php
            $result = $conn->query("SELECT filename FROM banners ORDER BY uploaded_at DESC");
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<img src="uploads/' . htmlspecialchars($row['filename']) . '" alt="Camp Banner">';
                }
            } else {
                echo "<p>No banners uploaded yet.</p>";
            }
            $conn->close();
            ?>
        </div>

        <br><br>
        <div class="button-container">
            <a href="camporganizeform.html" class="btn">Organize</a>
        </div>
    </main>

    <div class="moving-banner">
        <marquee behavior="scroll" direction="left">
            <span> One drop of blood you donate saves a life </span>
        </marquee>
    </div>

    <div style="height: 100px;"></div>

    <footer>
        <div class="footer-container">
            <div class="footer-column">
                <h4>ABOUT US</h4>
                <ul>
                    <li><a href="Aboutusnew.html">Purpose</a></li>
                    <li><a href="Privacy.html">Privacy Policy</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4>NEWS & BLOG</h4>
                <ul>
                    <li><a href="camp.php">Camps</a></li>
                    <li><a href="hospital.html">Locations</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4>RESOURCES</h4>
                <ul>
                    <li><a href="faq.html">FAQs</a></li>
                    <li><a href="Blooddonate.html">Blood donate</a></li>
                    <li><a href="Oragandonate.html">Organ donate</a></li>
                    <li><a href="Home.php">Rewards Program</a></li>
                    <li><a href="Contactus.html">Contact us</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4>CALL US</h4>
                <p class="call-number">011 3455434</p>
                <h4>FOLLOW US</h4>
                <div class="social-icons">
                    <a href="https://www.facebook.com/"><i class="fab fa-facebook"></i></a>
                    <a href="https://web.whatsapp.com/"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
                    <a href="https://lk.linkedin.com/"><i class="fab fa-linkedin"></i></a>
                    <a href="https://www.youtube.com/"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>Copyright © 2024 LifeLineLocator. All Rights Reserved.</p>
            <p>Terms of use | Privacy policy</p>
        </div>
    </footer>
</body>
</html>
