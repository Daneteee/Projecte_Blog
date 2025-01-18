<?php
require '../vendor/autoload.php';
require '../helpers.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Siusplau, completa tots los camps del formulari.',
        ];
        header('Location: contact.php');
        exit;
    }

    if (!validar_email($email)) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'El correo electrònic no és vàlid.',
        ];
        header('Location: contact.php');
        exit;
    }

    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dan.maldonado.2132@lacetania.cat';
        $mail->Password = 'XXXXXXX';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($email, $name);
        $mail->addAddress('dan.maldonado.2132@lacetania.cat', 'Dan Receptor');

        $mail->isHTML(true);
        $mail->Subject = 'Nou missatge';
        $mail->Body = "Nom: $name <br> Email: $email <br> Missatge: $message";

        $mail->send();
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Missatge enviat correctament.',
        ];
    } catch (Exception $e) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => "No s'ha pogut enviar. Error: {$mail->ErrorInfo}",
        ];
    }

    header('Location: contact.php');
    exit;
}
?>
