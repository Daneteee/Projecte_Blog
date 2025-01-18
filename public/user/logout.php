<?php
session_start();

// Tanquem la sessió
session_unset();
session_destroy();

header("Location: /index.php");
exit;
?>
