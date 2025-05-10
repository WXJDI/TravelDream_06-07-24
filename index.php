
<?php
session_start();
include("connect.php");


$sql_reviews = "SELECT * FROM reviews";
$result_reviews = $conn->query($sql_reviews);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <title>TravelDream | Web Design Mastery</title>
</head>

<body>
  <nav>
    <div class="nav__header">
      <div class="nav__logo">
        <a href="#" class="logo">Travel<span>Dream</span></a>
      </div>
      <div class="nav__menu__btn" id="menu-btn">
        <i class="ri-menu-line"></i>
      </div>
    </div>
    <ul class="nav__links" id="nav-links">
      <li><a href="#home">Home</a></li>
      <li><a href="#service">Services</a></li>
      <li><a href="#destination">Destinations</a></li>
      <li><a href="#package">Packages</a></li>
      <li><a href="#client">Clients</a></li>
      <?php if (isset($_SESSION['prenom'])): ?>
        <li><a href="my_bookings.php" class="my-bookings-btn">My Trips</a></li>
        <li><a href="logout.php">Sign Out</a></li>
        <li>Safe Travel <?php echo htmlspecialchars($_SESSION['prenom']); ?></li>
      <?php else: ?>
        <li><a href="indexSign.php">Sign In</a></li>
      <?php endif; ?>
    </ul>
  </nav>


  <header class="header" id="home">
    <div class="section__container header__container">
      <h1>TRAVELLER<br /><span>FOR LIFE.</span></h1>
      <p>Live your best moments</p>
      
    </div>
  </header>

  <section class="section__container feature__container" id="service">
    <div class="feature__card">
      <img src="assets/feature-1.png" alt="feature" />
      <div>
        <h4>Best Destinations</h4>
        <p>Discover the most breathtaking places around the globe.</p>
      </div>
    </div>
    <div class="feature__card">
      <img src="assets/feature-2.png" alt="feature" />
      <div>
        <h4>Best Price Guaranteed</h4>
        <p>Enjoy unbeatable prices on every trip you book.</p>
      </div>
    </div>
    <div class="feature__card">
      <img src="assets/feature-3.png" alt="feature" />
      <div>
        <h4>Instant Booking</h4>
        <p>Secure your dream vacation with just a click.</p>
      </div>
    </div>
  </section>

  <section class="destination" id="destination">
    <div class="section__container destination__container">
      <h2 class="section__header">Top Destinations</h2>
      <p class="section__description">
        Find out what are the best destinations in the world
      </p>
      <div class="destination__grid">
        <div class="destination__card">
          <img src="assets/destination-1.jpg" alt="Bhutan" />
          <div class="destination__content">Bhutan</div>
        </div>
        <div class="destination__card">
          <img src="assets/destination-2.jpg" alt="Japan" />
          <div class="destination__content">Japan</div>
        </div>
        <div class="destination__card">
          <img src="assets/destination-3.jpg" alt="Nepal" />
          <div class="destination__content">Nepal</div>
        </div>
      </div>
    </div>
  </section>

  <section class="discount">
    <div class="section__container discount__container">
      <h2>
        Get up to 60% discount<br /><span>by joining us before summer</span>
      </h2>
      
    </div>
  </section>

  <section class="section__container package__container" id="package">
    <h2 class="section__header">Featured Packages</h2>
    <p class="section__description">
      We will help you find the trip that's perfect for you!
    </p>
    <div class="package__grid">
      <?php
      $sql = "SELECT * FROM package";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo '<div class="package__card">';
          echo '<img src="' . $row['image_path'] . '" alt="package" />';
          echo '<div class="package__card__details">';
          echo '<h4>' . $row['package_name'] . '</h4>';
          echo '<p>' . $row['description'] . '</p>';
          echo '<div>';
          // ➔ Ici : lien avec l'id du package en paramètre GET
          echo '<a href="booking.php?package_id=' . $row['id'] . '" class="btn">Book Now</a>';
          echo '<h3>$' . $row['price'] . '</h3>';
          echo '</div>';
          echo '</div>';
          echo '</div>';
        }
      } else {
        echo "No packages available";
      }
      ?>
    </div>
  </section>



  <section class="section__container client__container" id="client">
    <h2 class="section__header">Client Reviews</h2>
    <p class="section__description">
      We have many happy customers who booked holidays with us
    </p>
    <div class="swiper">
      <div class="swiper-wrapper">
        <?php if ($result_reviews->num_rows > 0): ?>
          <?php while($review = $result_reviews->fetch_assoc()): ?>
            <div class="swiper-slide">
              <div class="client__card">
                <img src="<?php echo htmlspecialchars($review['image_path']); ?>" alt="client" />
                <span><i class="ri-double-quotes-l"></i></span>
                <p><?php echo htmlspecialchars($review['description']); ?></p>
                <h4><?php echo htmlspecialchars($review['name']); ?></h4>
                <h5><?php echo htmlspecialchars($review['job']); ?></h5>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p>No reviews available.</p>
        <?php endif; ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </section>


  <section class="subscribe">
    <div class="section__container subscribe__container">
      <h2>Subscribe to our newsletter for updates</h2>
      <div>
        <p>For the best recommendation, please subscribe to us.</p>
        <form action="/">
          <input type="email" placeholder="Enter Your Email Here" required />
          <button class="btn">SUBSCRIBE</button>
        </form>
      </div>
    </div>
  </section>

  <footer class="footer">
    <div class="section__container footer__container">
      <div class="footer__col">
        <div class="footer__logo">
          <a href="#" class="logo">Travel<span>Dream</span></a>
        </div>
        <p>Explore the world's best destinations, enjoy unbeatable prices, and book your perfect getaway instantly.</p>
        <h4>CONNECT WITH US</h4>
        <ul class="footer__socials">
          <li><a href="#"><i class="ri-twitter-fill"></i></a></li>
          <li><a href="#"><i class="ri-google-fill"></i></a></li>
          <li><a href="#"><i class="ri-linkedin-fill"></i></a></li>
        </ul>
      </div>

      <div class="footer__col">
        <h4>QUICK LINKS</h4>
        <ul class="footer__links">
          <li><a href="#">Home</a></li>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Blogs</a></li>
          <li><a href="#">Testimonials</a></li>
          <li><a href="#">Contact Us</a></li>
        </ul>
      </div>

      <div class="footer__col">
        <h4>DESTINATIONS</h4>
        <ul class="footer__links">
          <li><a href="#">China</a></li>
          <li><a href="#">Venezuela</a></li>
          <li><a href="#">Brazil</a></li>
          <li><a href="#">Australia</a></li>
          <li><a href="#">London</a></li>
        </ul>
      </div>

      <div class="footer__col">
        <h4>OUR ACTIVITIES</h4>
        <ul class="footer__links">
          <li><a href="#">Trekking</a></li>
          <li><a href="#">Peak Climbing</a></li>
          <li><a href="#">Biking</a></li>
          <li><a href="#">River Rafting</a></li>
          <li><a href="#">Cultural Tour</a></li>
        </ul>
      </div>
    </div>
    <div class="footer__bar">
      Copyright © 2025 TravelDream. All rights reserved.
    </div>
  </footer>

  <script src="https://unpkg.com/scrollreveal"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="js/script.js"></script>
</body>
</html>
