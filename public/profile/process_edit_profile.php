<?php
if (!isset($_SESSION)) {
    session_start();
}

include '../config/db.php';
include '../helpers.php'; 

$_SESSION['errors'] = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Netegem les dades
    $nom = cleanData($_POST['nom']);
    $email = cleanData($_POST['email']);
    $password = cleanData($_POST['password']);
    $user_id = $_SESSION['user_id'];

    // Validem el nom
    if (empty($nom) || !preg_match('/^[a-zA-Z ]+$/', $nom)) {
        $_SESSION['errors']['nom'] = "* El nom ha de contenir només lletres i espais.";
    }

    // Validem l'email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errors']['email'] = "* L'email no és vàlid.";
    } else {

        // Comprovem si ja está registrat
        $query = "SELECT id FROM usuaris WHERE email = ? AND id != ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['errors']['email'] = "* Aquest email ja està registrat per un altre usuari.";
        }
    }

    if (!empty($password)) {
        $current_password = cleanData($_POST['current_password']);
    
        if (empty($current_password)) {
            $_SESSION['errors']['current_password'] = "* Cal introduir la contrasenya actual per canviar-la.";
        } else {
            $query = "SELECT password FROM usuaris WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_data = $result->fetch_assoc();
    
            if (!password_verify($current_password, $user_data['password'])) {
                $_SESSION['errors']['current_password'] = "* La contrasenya actual no és correcta.";
            }
        }
    }
    

    // Actualitzem el perfil
    if (empty($_SESSION['errors'])) {
        $query = "UPDATE usuaris SET nom = ?, email = ?";
        $params = [$nom, $email];
        $types = "ss";

        if (!empty($password)) {
            $query .= ", password = ?";
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $params[] = $passwordHash;
            $types .= "s";
        }

        $query .= " WHERE id = ?";
        $params[] = $user_id;
        $types .= "i";

        $stmt = $db->prepare($query);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Perfil actualitzat correctament.'
            ];
        } else {
            $_SESSION['errors']['general'] = "* Hi ha hagut un error en actualitzar el perfil. Intenta-ho de nou.";
        }
    }

    header("Location: edit_profile.php");
    exit;
}
?>
