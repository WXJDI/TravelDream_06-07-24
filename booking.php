<?php
session_start();
include("connect.php");

// Récupérer le package depuis l'URL
$package_name = "";
$package_price = "";

if (isset($_GET['package_id'])) {
    $package_id = intval($_GET['package_id']);
    $sql = "SELECT * FROM package WHERE id = $package_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $package = $result->fetch_assoc();
        $package_name = $package['package_name'];
        $package_price = $package['price'];
    } else {
        $package_name = "Unknown Package";
        $package_price = "0";
    }
} else {
    $package_name = "No Package Selected";
    $package_price = "0";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking - TravelDream</title>
  <link rel="stylesheet" href="css/booking.css">
</head>
<body>
  <nav>
    <div class="nav__header">
      <div class="nav__logo">
        <a href="#" class="logo">Travel<span>Dream</span></a>
      </div>
      <ul class="nav__links">
        <li><a href="index.php">Home</a></li>
        <?php if (isset($_SESSION['prenom'])): ?>
          <li><a href="logout.php">Sign Out</a></li>
          <li style="color: black;">Safe Travel <?php echo htmlspecialchars($_SESSION['prenom']); ?></li>
        <?php else: ?>
          <li><a href="indexSign.php">Sign In</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>

  <section class="booking">
    <div class="section__container booking__container">
      <h1>
        Book Your Dream Trip 
        <?php if (!empty($package_name) && $package_name != "No Package Selected"): ?>
          - <?php echo htmlspecialchars($package_name); ?> ($<?php echo htmlspecialchars($package_price); ?>)
        <?php endif; ?>
      </h1>
      
      <form action="process_booking.php" method="POST" class="booking__form">
        <!-- Cacher le package_id pour l'envoyer dans process_booking.php -->
        <input type="hidden" name="package_id" value="<?php echo htmlspecialchars($package_id); ?>">
        <input type="hidden" name="destination" value="<?php echo htmlspecialchars($package_name); ?>">
        <input type="hidden" name="price" value="<?php echo htmlspecialchars($package_price); ?>">

        <div class="form__group">
          <label for="first_name">First Name</label>
          <input type="text" id="first_name" name="first_name" required>
        </div>
        <div class="form__group">
          <label for="last_name">Last Name</label>
          <input type="text" id="last_name" name="last_name" required>
        </div>
        <div class="form__group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form__group">
          <label for="date_departure">Departure Date</label>
          <input type="date" id="date_departure" name="date_departure" required>
        </div>
        <div class="form__group">
          <label for="date_arrival">Arrival Date</label>
          <input type="date" id="date_arrival" name="date_arrival" required>
        </div>
        <div class="form__group">
          <label for="number_of_people">Number of People</label>
          <input type="number" id="number_of_people" name="number_of_people" min="1" required>
        </div>
        <button type="submit" class="btn">Confirm Booking</button>
      </form>
    </div>
  </section>

  <footer class="footer">
    <div class="footer__bar">
      Copyright © 2025 TravelDream.
    </div>
  </footer>
</body>
</html>
