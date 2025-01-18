<?php
if (!isset($_SESSION)) {
    session_start();
}

include __DIR__ . '/../config/db.php'; 

if (!isset($_SESSION['errors'])) {
    $_SESSION['errors'] = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM usuaris WHERE email = ?";
    $stmt = $db->prepare($query);

    if (!$stmt) {
        die("Error en la preparació: " . $db->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nom'];
            $_SESSION['errors'] = [];
            
        } else {
            $_SESSION['errors']['login'] = "*Credencials incorrectes.";
        }
    } else {
        $_SESSION['errors']['login'] = "*Credencials incorrectes.";
    }

    $stmt->close();
    $db->close();

    header("Location: /index.php");
    exit;
}
?>
