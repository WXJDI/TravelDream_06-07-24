<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

require_once 'connect.php';

// Création d'une réservation
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create'])) {
    $stmt = $conn->prepare("INSERT INTO booking (user_id, package_id, date_dep, date_arr, number_of_people, booking_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissis",
        $_POST['user_id'],
        $_POST['package_id'],
        $_POST['date_dep'],
        $_POST['date_arr'],
        $_POST['number_of_people'],
        $_POST['booking_date']
    );
    $stmt->execute();
    header("Location: admin_bookings.php");
    exit();
}

// Modification d'une réservation
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE booking SET date_dep=?, date_arr=?, number_of_people=?, booking_date=? WHERE id=?");
    $stmt->bind_param("ssisi",
        $_POST['date_dep'],
        $_POST['date_arr'],
        $_POST['number_of_people'],
        $_POST['booking_date'],
        $_POST['id']
    );
    $stmt->execute();
    header("Location: admin_bookings.php");
    exit();
}

// Suppression
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM booking WHERE id=?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: admin_bookings.php");
    exit();
}

// Récupération des réservations avec jointures
$sql = "
    SELECT b.*, 
           u.firstName, u.lastName, 
           p.package_name 
    FROM booking b
    JOIN users u ON b.user_id = u.id
    JOIN package p ON b.package_id = p.id
";
$result = $conn->query($sql);
$bookings = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Réservations</title>
    <link rel="stylesheet" href="css/admin_users.css">
</head>
<body>

<nav class="admin-nav">
    <a href="admin_users.php">Utilisateurs</a>
    <a href="admin_packages.php">Packages</a>
    <a href="admin_reviews.php">Avis</a>
    <a href="admin_bookings.php" class="active">Réservations</a>
    <a href="logout.php" class="logout-btn">Déconnexion</a>
</nav>

<div class="admin-container">
    <h2>Gestion des Réservations</h2>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Package</th>
                <th>Date Départ</th>
                <th>Date Arrivée</th>
                <th>Personnes</th>
                <th>Date Réservation</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($bookings as $booking): ?>
            <tr>
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                    <td><?= htmlspecialchars($booking['firstName'] . ' ' . $booking['lastName']) ?></td>
                    <td><?= htmlspecialchars($booking['package_name']) ?></td>
                    <td><input type="date" name="date_dep" value="<?= $booking['date_dep'] ?>" required></td>
                    <td><input type="date" name="date_arr" value="<?= $booking['date_arr'] ?>" required></td>
                    <td><input type="number" name="number_of_people" value="<?= $booking['number_of_people'] ?>" required></td>
                    <td><input type="date" name="booking_date" value="<?= $booking['booking_date'] ?>" required></td>
                    <td>
                        <button type="submit" name="update" class="edit-btn">Modifier</button>
                        <a href="admin_bookings.php?delete=<?= $booking['id'] ?>" class="delete-btn" onclick="return confirm('Supprimer cette réservation ?');">Supprimer</a>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
