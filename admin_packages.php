<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

require_once 'connect.php';

// Création d'un package
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create'])) {
    // Validation et nettoyage du chemin de l'image
    $image_path = trim($_POST['image_path']);
    if (empty($image_path)) {
        $image_path = 'assets/default.jpg';
    } else {
        // Nettoyer le chemin et s'assurer qu'il commence par 'assets/'
        $image_path = 'assets/' . basename($image_path);
    }

    $stmt = $conn->prepare("INSERT INTO package (package_name, departure_date, arrival_date, price, description, image_path, number_of_people) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdssi",
        $_POST['package_name'],
        $_POST['departure_date'],
        $_POST['arrival_date'],
        $_POST['price'],
        $_POST['description'],
        $image_path,
        $_POST['number_of_people']
    );
    $stmt->execute();
    header("Location: admin_packages.php");
    exit();
}

// Modification d'un package
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
    // Validation et nettoyage du chemin de l'image
    $image_path = trim($_POST['image_path']);
    if (empty($image_path)) {
        $image_path = 'assets/default.jpg';
    } else {
        // Nettoyer le chemin et s'assurer qu'il commence par 'assets/'
        $image_path = 'assets/' . basename($image_path);
    }

    $stmt = $conn->prepare("UPDATE package SET package_name=?, departure_date=?, arrival_date=?, price=?, description=?, image_path=?, number_of_people=? WHERE id=?");
    $stmt->bind_param("sssdssii",
        $_POST['package_name'],
        $_POST['departure_date'],
        $_POST['arrival_date'],
        $_POST['price'],
        $_POST['description'],
        $image_path,
        $_POST['number_of_people'],
        $_POST['id']
    );
    $stmt->execute();
    header("Location: admin_packages.php");
    exit();
}

// Suppression
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM package WHERE id=?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: admin_packages.php");
    exit();
}

// Liste des packages
$result = $conn->query("SELECT * FROM package");
$packages = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Packages</title>
    <link rel="stylesheet" href="css/admin_users.css">
</head>
<body>

<nav class="admin-nav">
    <a href="admin_users.php">Utilisateurs</a>
    <a href="admin_packages.php" class="active">Packages</a>
    <a href="admin_reviews.php">Avis</a>
    <a href="admin_bookings.php">Réservations</a>
    <a href="logout.php" class="logout-btn">Déconnexion</a>
</nav>

<div class="admin-container">
    <h2>Gestion des Packages</h2>

    <form class="admin-form" method="POST" enctype="multipart/form-data">
        <input type="text" name="package_name" placeholder="Nom du package" required>
        <input type="date" name="departure_date" required>
        <input type="date" name="arrival_date" required>
        <input type="number" name="price" placeholder="Prix" step="0.01" required>
        <textarea name="description" placeholder="Description" rows="2" required></textarea>
        <input type="text" name="image_path" placeholder="Nom de l'image (ex: image.jpg)" required>
        <small>L'image sera enregistrée dans le dossier assets/</small>
        <input type="number" name="number_of_people" placeholder="Nombre de personnes" required>
        <button type="submit" name="create">Ajouter Package</button>
    </form>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Départ</th>
                <th>Arrivée</th>
                <th>Prix</th>
                <th>Description</th>
                <th>Image</th>
                <th>Personnes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($packages as $package): ?>
            <tr>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $package['id'] ?>">
                    <td><input type="text" name="package_name" value="<?= htmlspecialchars($package['package_name']) ?>" required></td>
                    <td><input type="date" name="departure_date" value="<?= $package['departure_date'] ?>" required></td>
                    <td><input type="date" name="arrival_date" value="<?= $package['arrival_date'] ?>" required></td>
                    <td><input type="number" name="price" value="<?= $package['price'] ?>" step="0.01" required></td>
                    <td><textarea name="description" rows="2" required><?= htmlspecialchars($package['description']) ?></textarea></td>
                    <td>
                        <input type="text" name="image_path" value="<?= htmlspecialchars(basename($package['image_path'])) ?>" required>
                        <br>
                        <img src="<?= htmlspecialchars($package['image_path']) ?>" alt="Image" style="max-width: 100px;">
                    </td>
                    <td><input type="number" name="number_of_people" value="<?= $package['number_of_people'] ?>" required></td>
                    <td>
                        <button type="submit" name="update" class="edit-btn">Modifier</button>
                        <a href="admin_packages.php?delete=<?= $package['id'] ?>" class="delete-btn" onclick="return confirm('Supprimer ce package ?');">Supprimer</a>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>