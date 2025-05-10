<?php
// Connecte-toi à la base de données
include("connect.php");

// Si l'ID de réservation est passé en paramètre, tu peux afficher des détails
if (isset($_GET['booking_id'])) {
    $booking_id = intval($_GET['booking_id']);
    $sql = "SELECT b.*, u.firstName, u.lastName, u.email, p.package_name AS destination, p.price 
            FROM booking b
            LEFT JOIN users u ON b.user_id = u.id
            LEFT JOIN package p ON b.package_id = p.id
            WHERE b.id = $booking_id";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $booking = $result->fetch_assoc();
    } else {
        echo "Booking not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thank You - TravelDream</title>
  <link rel="stylesheet" href="css/thank_you.css">
</head>
<body>
  <div class="thankyou-container">
    <header>
      <div class="logo">
        <a href="index.php" class="logo-link">TravelDream</a>
      </div>
    </header>
    <main class="thankyou-content">
      <div class="thankyou-message">
        <h1>Thank You for Your Booking!</h1>
        <p>Your booking has been confirmed. Here are your details:</p>
      </div>
      <div class="booking-details">
        <ul>
          <li><strong>Name:</strong> <?php echo htmlspecialchars(string: $booking['firstName']) . ' ' . htmlspecialchars($booking['lastName']); ?></li>
          <li><strong>Email:</strong> <?php echo htmlspecialchars($booking['email']); ?></li>
          <li><strong>Destination:</strong> <?php echo htmlspecialchars($booking['destination']); ?></li>
          <li><strong>Departure Date:</strong> <?php echo htmlspecialchars($booking['date_dep']); ?></li>
          <li><strong>Arrival Date:</strong> <?php echo htmlspecialchars($booking['date_arr']); ?></li>
          <li><strong>Number of People:</strong> <?php echo htmlspecialchars($booking['number_of_people']); ?></li>
          <li><strong>Total Price:</strong> $<?php echo htmlspecialchars($booking['price'] * $booking['number_of_people']); ?></li>
        </ul>
      </div>
      <div class="cta">
        <a href="index.php" class="btn">Return to Home</a>
      </div>
    </main>
    <footer>
      <p>Copyright © 2024 TravelDream.</p>
    </footer>
  </div>
</body>
</html>
