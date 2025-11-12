<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Life Line Locator</title>
  <link rel="stylesheet" href="Home.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
  <header>
    <div class="navbar">
      <nav>
        <ul>
          <li><a href="hospital.html">HOSPITAL</a></li>
          <li><a href="camp.php">CAMP</a></li>
          <li><a href="Aboutusnew.html">ABOUT</a></li>
          <li><a href="Contactus.html">CONTACT US</a></li>
        </ul>
      </nav>
      <div class="auth-buttons">
        <?php if (isset($_SESSION['username'])): ?>
          <span style="color:white; margin-right: 10px;">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
          <a href="logouthome.php" class="btn">SIGN OUT</a>
        <?php else: ?>
          <a href="register.php" class="btn">SIGN UP</a>
          <a href="login_db.php" class="btn">SIGN IN</a>
        <?php endif; ?>
      </div>
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
        <div class="buttons">
          <div class="dropdown">
            <button class="dropdown-btn">Blood</button>
            <div class="dropdown-content">
              <a href="Bloodrequest.html">Blood Request</a>
              <a href="Blooddonate.html">Blood Donate</a>
            </div>
          </div>

          <div class="dropdown">
            <button class="dropdown-btn">Organ</button>
            <div class="dropdown-content">
              <a href="Organrequest.html">Organ Request</a>
              <a href="Oragandonate.html">Organ Donate</a>
            </div>
          </div>
          <a href="Aboutusnew.html" class="btn">Learn More</a>
        </div>
      </div>
    </section>

    <section class="banner">
      <div class="banner-image">
        <img src="movingImage3.jpg" alt="Organ Donation">
      </div>
      <div class="banner-text">
        <h2>Donating Blood</h2>
        <p>Blood is made up of four main components. <br> Red blood cells, platelets, plasma, and white blood cells. <br>Each whole blood donation has the potential to save up to three lives.</p>
      </div>
    </section>

    <section class="banner2">
      <div class="banner-text">
        <h2>Because of You, Life Doesn’t Stop</h2>
        <p>Every four seconds, someone in Sri Lanka needs blood or platelets. <br>This could be a child battling dengue or a patient undergoing life-saving surgery. <br>If you’re hesitant about donating, rest assured—most donors describe it as a quick, <br>mild pinch! The process is safe, efficient, and deeply rewarding, <br>knowing you might save up to three lives with one donation.</p>
        <a href="BecauseofYou.html" class="btn">Learn More</a>
      </div>
    </section>

    <div class="blood-type-image">
      <?php
      $bloodTypes = ["AB+", "AB-", "A+", "A-", "B+", "B-", "O+", "O-"];
      foreach ($bloodTypes as $type) {
        echo '
          <div class="blood-type-item">
            <img src="bloodbag.png" alt="blood-type-' . $type . '">
            <a href="' . $type . '.html">' . $type . '</a>
          </div>';
      }
      ?>
    </div>

    <section class="info-cards">
      <div class="card">
        <h3>Learn about Donating Organ</h3>
        <p>Discover every step of the organ donation process.</p>
        <a href="LearnOrgan.html" class="btn">Learn More</a>
      </div>
      <div class="card">
        <h3>Learn about Donating Blood</h3>
        <p>Discover every step of the blood donation process.</p>
        <a href="LearnBlood.html" class="btn">Learn More</a>
      </div>
      <div class="card">
        <h3>Are You Eligible?</h3>
        <p>Check the eligibility criteria for donating blood safely.</p>
        <a href="CheckEligibility.html" class="btn">Check Eligibility</a>
      </div>
    </section>

    <div class="container">
      <div class="info-box">
        <h2>Facts About Blood Needs</h2>
        <div class="content">
          <p>In Sri Lanka, blood is needed approximately every 4 seconds to support critical medical treatments.</p>
          <p>Nearly 2,500 units of blood are required daily to meet the demand in hospitals across the country.</p>
          <p>Organ transplants are in high demand, with hundreds waiting for donors to save their lives.</p>
        </div>
      </div>
      <div class="info-box">
        <h2>What Happens to Donated Blood?</h2>
        <div class="content">
          <p>Have you ever wondered what happens to the blood you donate? It goes on an amazing journey!</p>
          <p>Learn about all the steps your blood goes through before reaching a recipient.</p>
        </div>
      </div>
    </div>

    <div class="button-container">
      <div class="button-item"><i class="icon">❤️</i> ORGAN</div>
      <div class="button-item"><i class="icon">🩸</i> BLOOD</div>
      <div class="button-item"><i class="icon">📝</i> DETAILS</div>
      <div class="button-item"><a href="Organrequest.html"><i class="icon">❤️</i> ORGAN REQUEST</a></div>
      <div class="button-item"><a href="Bloodrequest.html"><i class="icon">🩸</i> BLOOD REQUEST</a></div>
      <div class="button-item"><a href="Contactus.html"><i class="icon">👥</i> CONTACT US</a></div>
      <div class="button-item"><a href="Oragandonate.html"><i class="icon">❤️</i> ORGAN DONATE</a></div>
      <div class="button-item"><a href="Blooddonate.html"><i class="icon">🩸</i> BLOOD DONATE</a></div>
      <div class="button-item">
    <?php if (isset($_SESSION['role'])): ?>
        <a href="dashboard/<?php echo $_SESSION['role']; ?>.php"><i class="icon">🏥</i> Availability</a>
    <?php else: ?>
        <a href="login_dbNew.php"><i class="icon">🏥</i> Availability</a>
    <?php endif; ?>
</div>

    </div>
  </main>

  <div class="moving-banner">
    <marquee behavior="scroll" direction="left">
      <span> One drop of blood you donate saves a life </span>
    </marquee>
  </div>

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
          <li><a href="Organdonate.html">Organ donate</a></li>
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
