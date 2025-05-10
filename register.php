<?php
include 'connect.php';

if (isset($_POST['signUp'])) {
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    // Par défaut, tout nouveau compte est un "user" normal
    $role = 'user'; 

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        echo "Email Address Already Exists !";
    } else {
        $insertQuery = "INSERT INTO users(firstName, lastName, email, password, role)
                        VALUES ('$firstName', '$lastName', '$email', '$password', '$role')";
        if ($conn->query($insertQuery) === TRUE) {
            header("location: indexSign.php");
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        $_SESSION['prenom'] = $row['firstName'];
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['role'] = $row['role']; // 🔥 ajout important

        if ($row['role'] == 'admin') {
            header("Location: admin_users.php"); // redirection vers page admin
        } else {
            header("Location: index.php"); // redirection utilisateur normal
        }
        exit();
    } else {
        echo "Not Found, Incorrect Email or Password";
    }
}
?>
