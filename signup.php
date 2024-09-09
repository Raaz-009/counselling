<?php
include("./config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['Full_Name'];
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    if (empty($full_name) || empty($username) || empty($email) || empty($password) || empty($user_type)) {
        die("Please fill in all required fields.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    $password_hashed = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (Full_Name, username, phone_number, email, password, user_type) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $full_name, $username, $phone_number, $email, $password_hashed, $user_type);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['user_type'] = $user_type;
        $_SESSION['Full_Name'] = $full_name;
        echo "Signup successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
