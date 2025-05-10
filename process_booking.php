<?php
session_start();
include("connect.php");

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier que l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        echo "You must be logged in to book.";
        exit;
    }

    // Récupérer et sécuriser les données du formulaire
    $user_id = intval($_SESSION['user_id']); // On récupère le user connecté
    $package_id = intval($_POST['package_id']); // Depuis le formulaire (hidden input par exemple)
    $date_departure = mysqli_real_escape_string($conn, $_POST['date_departure']);
    $date_arrival = mysqli_real_escape_string($conn, $_POST['date_arrival']);
    $number_of_people = intval($_POST['number_of_people']);

    // Préparer la requête d'insertion
    $sql = "INSERT INTO booking (user_id, package_id, date_dep, date_arr, number_of_people)
            VALUES ($user_id, $package_id, '$date_departure', '$date_arrival', $number_of_people)";

    // Exécuter la requête
    if ($conn->query($sql) === TRUE) {
        // Rediriger vers la page de confirmation avec l'ID de la réservation
        header("Location: thank_you.php?booking_id=" . $conn->insert_id);
        exit();
    } else {
        echo "Erreur lors de l'enregistrement : " . $conn->error;
    }
}

// Fermer la connexion
$conn->close();
?>
