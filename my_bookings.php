<?php
session_start();
include("connect.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {  // Utiliser 'user_id' au lieu de 'prenom'
    header("Location: indexSign.php");
    exit();
}

// Récupérer les réservations de l'utilisateur
$user_id = $_SESSION['user_id'];  // Récupérer l'ID utilisateur depuis la session
$sql = "SELECT b.*, p.package_name, p.price FROM booking b INNER JOIN package p ON b.package_id = p.id WHERE b.user_id = $user_id";  // Joindre la table 'package' pour obtenir le nom du package
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Reservations - TravelDream</title>
  <link rel="stylesheet" href="css/my_bookings.css">
</head>
<body>
  <header>
    <div class="logo">
      <a href="index.php" class="logo-link">TravelDream</a>
    </div>
  </header>
  <main class="my-bookings-container">
    <h1>My Reservations</h1>
    <?php if ($result->num_rows > 0): ?>
      <table class="reservations-table">
        <thead>
          <tr>
            <th>Package</th> <!-- Afficher le nom du package -->
            <th>Departure Date</th>
            <th>Arrival Date</th>
            <th>Number of People</th>
            <th>Total Price</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($booking = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($booking['package_name']); ?></td> <!-- Affichage du nom du package -->
              <td><?php echo htmlspecialchars($booking['date_dep']); ?></td>
              <td><?php echo htmlspecialchars($booking['date_arr']); ?></td>
              <td><?php echo htmlspecialchars($booking['number_of_people']); ?></td>
              <td>$<?php echo number_format($booking['price'] * $booking['number_of_people'], 2); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No reservations found.</p>
    <?php endif; ?>
  </main>
  <footer>
    <p>Copyright © 2024 TravelDream.</p>
  </footer>
</body>
</html>
