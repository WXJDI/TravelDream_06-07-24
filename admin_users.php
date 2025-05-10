<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Ajouter un utilisateur
if (isset($_POST['add_user'])) {
    $firstName = $_POST['firstName'];
    $lastName  = $_POST['lastName'];
    $email     = $_POST['email'];
    $password  = md5($_POST['password']);
    $role      = $_POST['role'];

    $conn->query("INSERT INTO users (firstName, lastName, email, password, role)
                  VALUES ('$firstName', '$lastName', '$email', '$password', '$role')");
}

// Supprimer un utilisateur
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id = $id");
}

// Modifier un utilisateur
if (isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $firstName = $_POST['firstName'];
    $lastName  = $_POST['lastName'];
    $email     = $_POST['email'];
    $role      = $_POST['role'];

    // Si un nouveau mot de passe est fourni, on le met à jour
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $conn->query("UPDATE users SET firstName='$firstName', lastName='$lastName', email='$email', password='$password', role='$role' WHERE id=$id");
    } else {
        $conn->query("UPDATE users SET firstName='$firstName', lastName='$lastName', email='$email', role='$role' WHERE id=$id");
    }
}

$users = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer Utilisateurs</title>
    <link rel="stylesheet" href="css/admin_users.css">
    <style>
        .inline-form input, .inline-form select {
            width: 100%;
            padding: 5px;
            font-size: 14px;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .action-buttons form {
            display: inline;
        }
    </style>
</head>
<body>

<nav class="admin-nav">
    <a href="admin_users.php" class="active">Utilisateurs</a>
    <a href="admin_packages.php">Packages</a>
    <a href="admin_reviews.php">Avis</a>
    <a href="admin_bookings.php">Réservations</a>
    <a href="logout.php" class="logout-btn">Déconnexion</a>
</nav>

<main class="admin-container">
    <section class="admin-section">
        <h2>Ajouter un utilisateur</h2>
        <form method="POST" class="admin-form">
            <input type="text" name="firstName" placeholder="Prénom" required>
            <input type="text" name="lastName" placeholder="Nom" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <select name="role" required>
                <option value="user">Utilisateur</option>
                <option value="admin">Administrateur</option>
            </select>
            <button type="submit" name="add_user">Ajouter</button>
        </form>
    </section>

    <section class="admin-section">
        <h2>Liste des utilisateurs</h2>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Mot de passe</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $users->fetch_assoc()): ?>
                <tr>
                    <form method="POST" class="inline-form">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <td><input type="text" name="firstName" value="<?= htmlspecialchars($row['firstName']) ?>" required></td>
                        <td><input type="text" name="lastName" value="<?= htmlspecialchars($row['lastName']) ?>" required></td>
                        <td><input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required></td>
                        <td><input type="password" name="password" placeholder="Laisser vide pour ne pas changer"></td>
                        <td>
                            <select name="role" required>
                                <option value="user" <?= $row['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
                                <option value="admin" <?= $row['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                            </select>
                        </td>
                        <td class="action-buttons">
                            <button type="submit" name="update_user" class="edit-btn">Enregistrer</button>
                            <a href="admin_users.php?delete=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</main>

</body>
</html>
