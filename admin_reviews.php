<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

require_once 'connect.php'; // Utilise $conn avec MySQLi

// Création d'un avis
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create'])) {
    $stmt = $conn->prepare("INSERT INTO reviews (image_path, description, name, job) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss",
        $_POST['image_path'],
        $_POST['description'],
        $_POST['name'],
        $_POST['job']
    );
    $stmt->execute();
    header("Location: admin_reviews.php");
    exit();
}

// Modification d'un avis
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE reviews SET image_path=?, description=?, name=?, job=? WHERE id=?");
    $stmt->bind_param("ssssi",
        $_POST['image_path'],
        $_POST['description'],
        $_POST['name'],
        $_POST['job'],
        $_POST['id']
    );
    $stmt->execute();
    header("Location: admin_reviews.php");
    exit();
}

// Suppression d'un avis
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM reviews WHERE id=?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: admin_reviews.php");
    exit();
}

// Récupération des avis
$result = $conn->query("SELECT * FROM reviews");
$reviews = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Avis</title>
    <link rel="stylesheet" href="css/admin_users.css">
</head>
<body>

<nav class="admin-nav">
    <a href="admin_users.php">Utilisateurs</a>
    <a href="admin_packages.php">Packages</a>
    <a href="admin_reviews.php" class="active">Avis</a>
    <a href="admin_bookings.php">Réservations</a>
    <a href="logout.php" class="logout-btn">Déconnexion</a>
</nav>

<div class="admin-container">
    <h2>Gestion des Avis</h2>

    <form class="admin-form" method="POST">
        <input type="text" name="image_path" placeholder="Chemin de l'image" required>
        <textarea name="description" placeholder="Description" rows="3" required></textarea>
        <input type="text" name="name" placeholder="Nom" required>
        <input type="text" name="job" placeholder="Poste" required>
        <button type="submit" name="create">Ajouter Avis</button>
    </form>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Description</th>
                <th>Nom</th>
                <th>Poste</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($reviews as $review): ?>
            <tr>
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $review['id'] ?>">
                    <td><input type="text" name="image_path" value="<?= htmlspecialchars($review['image_path']) ?>" required></td>
                    <td><textarea name="description" rows="3"><?= htmlspecialchars($review['description']) ?></textarea></td>
                    <td><input type="text" name="name" value="<?= htmlspecialchars($review['name']) ?>" required></td>
                    <td><input type="text" name="job" value="<?= htmlspecialchars($review['job']) ?>" required></td>
                    <td>
                        <button type="submit" name="update" class="edit-btn">Modifier</button>
                        <a href="admin_reviews.php?delete=<?= $review['id'] ?>" class="delete-btn" onclick="return confirm('Supprimer cet avis ?');">Supprimer</a>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
