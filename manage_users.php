<?php
session_start();
include 'connect.php';

// Vérification de sécurité : seuls les admins peuvent accéder ici
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Récupérer les utilisateurs
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les Utilisateurs</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <style>
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .user-table th, .user-table td {
            border: 1px solid #ccc;
            padding: 12px 15px;
            text-align: center;
        }

        .user-table th {
            background-color: #0077b6;
            color: white;
        }

        .user-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            text-decoration: none;
        }

        .edit-btn {
            background-color: #00b4d8;
        }

        .edit-btn:hover {
            background-color: #0096c7;
        }

        .delete-btn {
            background-color: #d00000;
        }

        .delete-btn:hover {
            background-color: #9e0000;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            background-color: #023e8a;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
        }

        .back-btn:hover {
            background-color: #0077b6;
        }
    </style>
</head>
<body>

<header class="admin-header">
    <h1>Gestion des Utilisateurs</h1>
</header>

<div class="admin-main">
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Mot de passe (hash)</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['firstName']) ?></td>
                    <td><?= htmlspecialchars($row['lastName']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['password']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $row['id'] ?>" class="action-btn edit-btn">Modifier</a>
                        <a href="delete_user.php?id=<?= $row['id'] ?>" class="action-btn delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Aucun utilisateur trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php" class="back-btn">Retour au Dashboard</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
