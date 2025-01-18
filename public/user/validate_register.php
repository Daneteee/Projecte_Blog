<?php
if (!isset($_SESSION)) {
    session_start();
}

include __DIR__ . '/../config/db.php';

// Netegem les dades
function cleanData($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Funció per validar la contrasenya
function validar_contrasenya($password) {
    return !empty($password) &&
        preg_match('/[A-Z]/', $password) &&  
        preg_match('/[a-z]/', $password) &&  
        preg_match('/[0-9]/', $password) &&  
        preg_match('/[!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]+/', $password) && 
        strlen($password) >= 8;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $_SESSION['errors'] = []; // Inicialitzem l'array d'errors

    // Netegem els camps
    $nom = cleanData($_POST['nom']);
    $cognom = cleanData($_POST['cognom']);
    $email = cleanData($_POST['email']);
    $password = cleanData($_POST['password']);
    $data = date('Y-m-d');

    // Validem el nom
    if (empty($nom) || !preg_match('/^[a-zA-Z ]+$/', $nom)) {
        $_SESSION['errors']['nom'] = "* El nom ha de contenir només lletres i espais.";
    }

    // Validem el cognom
    if (empty($cognom) || !preg_match('/^[a-zA-Z ]+$/', $cognom)) {
        $_SESSION['errors']['cognom'] = "* El cognom ha de contenir només lletres i espais.";
    }

    // Validem la contrasenya
    if (!validar_contrasenya($password)) {
        $_SESSION['errors']['password'] = "* La contrasenya ha de tenir almenys 8 caràcters, incloent 1 majúscula, 1 minúscula, 1 xifra i 1 caràcter especial.";
    }

    // Validem l'email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['errors']['email'] = "* L'email no és vàlid.";
    }

    // Si no hi ha errors, processem el registre
    if (empty($_SESSION['errors'])) {

        // Verifiquem si l'email ja està registrat
        $checkEmailQuery = "SELECT * FROM usuaris WHERE email = ?";
        $stmt = $db->prepare($checkEmailQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['errors']['email'] = "* Aquest email ja està registrat.";
        } else {
            // Registrem el nou usuari
            $query = "INSERT INTO usuaris (nom, cognom, email, password, data) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("sssss", $nom, $cognom, $email, $passwordHash, $data);

            if ($stmt->execute()) {
                // Redirigim a la pàgina principal si el registre ha estat exitós
                header("Location: /index.php");
                exit;
            } else {
                $_SESSION['errors']['general'] = "* Error al registrar l'usuari. Intenta-ho de nou.";
            }
        }

        $stmt->close();
    }

    header("Location: /index.php");
    exit;
}
?>
